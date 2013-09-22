<!DOCTYPE html>
    
<html>
    <?php
        $debug = True;
        // Connect to transaction database
		$dbhost = "localhost";
		$dbname = "transaction";
		$dbuser = "root";
		mysql_connect($dbhost,$dbuser) or die(mysql_error());
		mysql_select_db($dbname) or die(mysql_error());
    ?>

    <head>
        <title>Transaction History</title>
        
        <style type="text/css" media="screen">
            @import url("style2.css");
            @import url("styling.css");
        </style>
        <script>
            // Expand function
            function expand(showMe)
            {
                if(document.getElementById(showMe).style.display=='none')
                    display = 'block';
                else
                    display = 'none';

                document.getElementById(showMe).style.display = display;
            }
        </script>
    </head>

    <body id="main">
        <div id="box">
        <?php 
            if($debug) print_r($_GET);
            
            //mandatory search parameters
            $pg = (key_exists("pg", $_GET)) ? $_GET["pg"] : 1;    //page number
            $ts = (key_exists("ts", $_GET)) ? $_GET["ts"] : 0;    //Starting transaction
            $tn = (key_exists("tn", $_GET)) ? $_GET["tn"] : 20;   //Number of transactions per page
            $tf = $ts + $tn;
            $oc = (key_exists("oc", $_GET)) ? $_GET["oc"] : "TransactionDate"; //Order-by column
            $od = (key_exists("od", $_GET)) ? $_GET["od"] : "DESC"; //Order direction
            
            //Non-mandatory 
            $kw = (key_exists("kw", $_GET)) ? $_GET["kw"] : "";   //Keywords
            $fd = (key_exists("fd", $_GET)) ? $_GET["fd"] : Null; 
            $td = (key_exists("td", $_GET)) ? $_GET["td"] : Null; 
            $st = (key_exists("st", $_GET)) ? $_GET["st"] : "0";
            
            //if (isAdmin()) echo "<a class=\"button-administration\">Administration</a>"
        ?>      
        <h1>Transaction History</h1>
        <form method="get" action="search.php" class="content">        
            <div class="bordered">
            <h2 style="float:left">Search</h2>
            <input type="submit" id="search-button" value="Update" Style="float:right">                   
            <input type="hidden" name="pg" value=<?php echo $pg?>>          
            <input type="hidden" name="ts" value=<?php echo $ts?>>
            <input type="hidden" name="tn" value=<?php echo $tn?>>
            <input type="hidden" name="oc" value=<?php echo $oc?>>          
            <input type="hidden" name="od" value=<?php echo $od?>>
            <table id="basic-options">
                <thead>
                    <tr>
                        <td>Keywords</td>
                        <td>From date</td>
                        <td>To date</td>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><input type="text" name="kw" value=<?php echo $kw?>></td>
                        <td><input type="date" name="fd" value=<?php echo $fd?>></td>
                        <td><input type="date" name="td" value=<?php echo $td?>></td>
                    </tr>
                </tbody>
            </table>
            <table id="advanced-options">
                <thead>
                    <tr>
                        <td>Status</td>
                        <!-- to do: more options -->
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            <select name="st">
                                <option value=0>-- Select --</option>
                                <?php         
                                    $statuses = mysql_query("SELECT * FROM Status") or die(mysql_error());
                                    while($row = mysql_fetch_array($statuses)){
                                        $sel = ($row['ID']==$st) ? "selected" : "";
                                        echo "<option value=".$row['ID']." ".$sel." >".$row['Name']."</option>";
                                    }
                                ?>
                            </select>
                        </td>
                    </tr>
                </tbody>
            </table>
            <a class="expander">Expand</a><!-- to do: write this properly in JavaScript -->
            </div><!-- end bordered-->
                   
            <?php              
                //select the transactions to display on the page
                $qry = "WHERE "
                $qrystate = ($st == 0)? "" : 
                    "StatusID = ".$st."\n";
                $qrydate  = ($fd == Null OR $td == Null)? "" : 
                    "TransactionDate BETWEEN ".$fd." AND ".$td."\n";
                $qrykey   = ($kw == "")? "" :
                    "Description LIKE '%".$kw."%' OR Comment LIKE '%".$kw."%'"; 
                
                $sql="
                    SELECT 
                        History.ID AS HistoryID,
                        History.TransactionID AS TransactionID, 
                        DATE(History.TransactionDate) AS TransactionDate, 
                        History.Description AS Description, 
                        History.Amount AS Amount, 
                        Status.Name AS Status,
                        Status.ID AS StatusID
                    FROM (
                        SELECT TransactionID, Max(ModificationDate) AS ModificationDate
                        FROM History 
                        GROUP BY TransactionID
                    ) AS Latest
                    INNER JOIN History ON Latest.TransactionID = History.TransactionID
                    AND Latest.ModificationDate = History.ModificationDate
                    INNER JOIN Status ON Status.ID = History.StatusID
                    " . $qrystate . $qrydate .
                   "ORDER BY " . $oc . " " . $od . "
                    LIMIT " . $ts . ", " . $tf . ";";
                echo $sql;
                $page = mysql_query($sql) or die(mysql_error());
            ?>

            <table id="transaction-list" summary = "List of Transactions">
                <thead>
                    <td>Trans. ID</td>
                    <td>Trans. Date</td>
                    <td>Description</td>
                    <td>Status</td>
                    <td>Amount</td>
                    <td></td>
                </thead>
                <tbody>
                <?php
                    while($row = mysql_fetch_array($page))
                    {
                ?>
                    <tr id = >
                        <td><?php echo $row['TransactionID']    ?></td>
                        <td><?php echo $row['TransactionDate']  ?></td>
                        <td><?php echo $row['Description']      ?></td>
                        <td><?php echo $row['Status']           ?></td>
                        <td><?php echo $row['Amount'] / 100     ?></td>
                        <td><a href="transaction.php?hid=<?php echo $row['HistoryID'] ?>>Edit</a></td>
                    </tr>
                <?php
                    }
                ?>
                </tbody>
            </table>
        <div id="pagination"> 
        <a>Back</a><!-- to do: write this properly in JavaScript - decrements start and submits form-->
        <a>Forward</a><!-- to do: write this properly in JavaScript - increments start and submits form-->
        </div><!-- end pagination -->    
        </form>
        
    </div><!-- end box -->
    <div id="sidebar">
        <!--<iframe src="/sidebar.html"/>-->
    </div><!-- end sidebar -->
    </body>
</html>

<?php
require_once 'classes/authentication.php';
require_once 'classes/mysql.php';
require_once 'includes/constants.php';
?>


<!DOCTYPE html> 
<html>
    <?php
        // Connect to transaction database

		mysql_connect(DB_SERVER, DB_USER, DB_PASSWORD) or die(mysql_error());
		mysql_select_db(DB_NAME) or die(mysql_error());
		
		// Inserting new category
		if (key_exists("catName", $_POST) && key_exists("catDesc", $_POST))
		{
			$catName = $_POST['catName'];
			$description = $_POST['catDesc'];
			
			// Insert values
			$slq="INSERT INTO category (Name, Description) VALUES ('$catName','$description')";
			mysql_query($slq,$con);
			
			echo "One more category was inserted";
		}
    ?>

    <head>
        <title>Transaction History</title>
        <link rel="stylesheet" type="text/css" href="/css/style2.css">
        <link rel="stylesheet" type="text/css" href="/css/styling.css">
        <script src="/js/expander.js"></script>
    </head>

    <body id="main">
        <div id="box">
        <?php 
            if(DEBUG) print_r($_GET);
            
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
            <?php
            echo "<input type='hidden' name='pg' value=".$pg.">";         
            echo "<input type='hidden' name='ts' value=".$ts.">";
            echo "<input type='hidden' name='tn' value=".$tn.">";
            echo "<input type='hidden' name='oc' value=".$oc.">";       
            echo "<input type='hidden' name='od' value=".$od.">";
            ?>

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
                    <?php
                    echo "<td><input type='text' name='kw' value=".$kw."></td>";
                    echo "<td><input type='date' name='fd' value=".$fd."></td>";
                    echo "<td><input type='date' name='td' value=".$td."></td>";
                    ?>
                    </tr>
                </tbody>
            </table>
            <table id="advanced-options" style="display:none">
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
            <button id="search-expander" 
                onclick="showID(advanced-options); 
                         hideID(search-expander); 
                         showID(search-hider)">Show advanced options</button>
            <button id="search-hider" style="display:none" 
                onclick="hideID(advanced-options); 
                         hideID(search-hider);
                         showID(search-expander);">Hide advanced options</button>
            </div><!-- end bordered-->
                   
            <?php              
                //select the transactions to display on the page
                $whr = "";
                if ($st != 0) {
                    $whr = $whr . "StatusID = ".$st."\nAND ";
                }
                if ($fd != Null AND $td != Null) {
                    $whr = $whr . "TransactionDate BETWEEN ".$fd." AND ".$td."\nAND ";
                }
                if ($kw != "") {
                    $whr = $whr . "History.Description LIKE '%".$kw."%' OR Comment LIKE '%".$kw."%'\nAND "; 
                }
                if (substr($whr,-4) == "AND ") {
                    $whr = substr($whr,0,strlen($whr)-4);
                }
                if ($whr != "") {
                    $whr = "WHERE " . $whr;
                }
                echo "qry: " . $whr . "<br/>--<br/>";
                
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
                    " . $whr .
                   "ORDER BY " . $oc . " " . $od . "
                    LIMIT " . $ts . ", " . $tf . 
                  ";";
                echo "sql: " . $sql . "<br/>";
                $page = mysql_query($sql) or die(mysql_error());
            ?>

            <table id="transaction-list" summary = "List of Transactions">
                <thead>
                    <td>Transaction<br/>ID</td>
                    <td>Transaction<br/>Date</td>
                    <td>Description</td>
                    <td>Status</td>
                    <td>Amount</td>
                    <td></td>
                </thead>
                <tbody>
                <?php
                    while($row = mysql_fetch_array($page))
                    {
                        echo "<tr>";
                        echo "<td>". $row['TransactionID'] . "</td>";
                        echo "<td>". $row['TransactionDate'] . "</td>";
                        echo "<td>". $row['Description']  . "</td>";
                        echo "<td>". $row['Status'] . "</td>";
                        echo "<td>". $row['Amount'] / 100 . "</td>";
                        echo "<td><a id='edit' href='transaction.php?hid=" . $row['HistoryID'] . "'>Edit</a></td>";
                        echo "</tr>";
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
            <?php include_once("sidebar.php");?>
            </div>
    </body>
</html>

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

    <body>
    <div id="main">
        <div id="box">
			<?php 
				//mandatory search parameters
				$page = (in_array("pg", $_GET)) ? $_GET["pg"] : 1;  //<!-- page number -->
				$tstr = (in_array("ts", $_GET)) ? $_GET["ts"] : 0;  //<!-- Starting transaction -->
				$tnum = (in_array("tn", $_GET)) ? $_GET["tn"] : 20; //<!-- Number of transactions per page -->
				$tfin = $tstr + $tnum;
				$ocol = (in_array("oc", $_GET)) ? $_GET["oc"] : "TransactionDate"; //<!-- Order by column -->
				$odir = (in_array("tn", $_GET)) ? $_GET["tn"] : "DESC"; //<!-- Order direction -->
			?>
			
			<?php
				//if (isAdmin()) echo "<a>Administration</a>"
			?>
            <h1>Transaction History</h1>
            <div id="content">
                <form <table class="bordered" method="get" action="search.php" id="search-form">        
                    <h2>Search</h2>
                    <input type="submit" id="search-update"/>                   
                    <input type="hidden" name="pg" value=<?php echo $page;?>/>          
                    <input type="hidden" name="ts" value=<?php echo $tstr;?>/>
                    <input type="hidden" name="tn" value=<?php echo $tnum;?>/>
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
                                <td><input type="text" name="kw" 
									<?php if (in_array("kw", $_GET)) echo "value=" . $_GET["kw"]; ?> /></td>
                                <td><input type="date" name="fd" 
									<?php if (in_array("fd", $_GET)) echo "value=" . $_GET["fd"]; ?> /></td>
                                <td><input type="date" name="td" 
									<?php if (in_array("td", $_GET)) echo "value=" . $_GET["td"]; ?> /></td>
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
                                    <select name="st" <?php if (in_array("st", $_GET)) echo "value=".$_GET["st"]; ?>>
                                        <option>-- Select --</option>
                                        <?php         
                                            $statuses = mysql_query("SELECT * FROM Status") or die(mysql_error());
                                            while($row = mysql_fetch_array($statuses)){
                                                echo "<option value=".$row['ID'].">".$row['Name']."</option>";
                                            }
                                        ?>
                                    </select>
                                </td>
                            </div>
                        </tbody>
                    </table>
                    <a class="expander">Expand</a><!-- to do: write this properly in JavaScript -->
                </form>
                       
                <?php              
					//select the transactions to display on the page
                    $sql="
                        SELECT 
                            History.TransactionID AS TransactionID, 
                            DATE(History.TransactionDate) AS TransactionDate, 
                            History.Description AS Description, 
                            History.Amount AS Amount, 
                            Status.Name AS Status 
                        FROM (
                            SELECT TransactionID, Max(History.ModificationDate) AS ModificationDate
                            FROM History 
                            GROUP BY TransactionID 
                        ) AS Latest
                        INNER JOIN History 
                        ON Latest.TransactionID = History.TransactionID AND Latest.ModificationDate = History.ModificationDate
                        INNER JOIN Status
                        ON Status.ID = History.StatusID
                        " .
                        ""//WHERE
                        . "
						ORDER BY " . $ocol . " " . $odir . "
						LIMIT " . $tstr . ", " . $tfin . ";";
					$page = mysql_query($sql) or die(mysql_error());
                ?>

                <table class="bordered" id="transaction-list" summary = "List of Transactions">
                    <thead>
                        <td>Transaction ID</td>
                        <td>Transaction Date</td>
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
                            <td><a href="transaction.php?tid=<?php echo $row['TransactionID'] ?>?Td">Edit</a></td>
                        </tr>
                    <?php
                        }
                    ?>
                    </tbody>
                </table>
                <div id="pagination"> 
                    <a>Back</a><!-- to do: write this properly in JavaScript - decrements start and submits form-->
					<a>Forward</a><!-- to do: write this properly in JavaScript - increments start and submits form-->
                </ul>
            </div><!-- end content -->
        </div><!-- end box -->
        <div id="sidebar">
            <iframe src="/sidebar.html"/>
        </div><!-- end sidebar -->
    </div><!-- end main -->
    </body>
</html>

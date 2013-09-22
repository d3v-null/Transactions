<!DOCTYPE html>
    
<html>
    <?php
        $debug = True;
        // Connect to transaction database
		$dbhost = "localhost";
		$dbname = "transactions";
		$dbuser = "transactions";
		$dbpass = "";
		mysql_connect($dbhost,$dbuser,$dbpass) or die(mysql_error());
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
                <form <table class="bordered" method="get" action="search.html" id="search-form">        
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
                                    <select name="st" <?php if (in_array("st")) echo "value=" . $_GET["st"]; ?>>
                                        <?php         
                                            $statuses = mysql_query("SELECT * FROM Statuses;") or die(mysql_error());
                                            while($row = mysql_fetch_array($statuses)){
                                                echo "<option value=\"" . $row['Name'] . "\">" . $row['Name'] . "</option>";
                                            }
                                        ?>
                                    </select>
                                </td>
                            </div>
                        </tbody>
                    </table>
                    <div id="expander">"Expand"</div><!-- to do: write this properly in JavaScript -->
                </form>
                       
                <?php              
					//select the transactions to display on the page
                    $sql="
						SELECT HistoryID, TransactionID, TransactionDate, Description, Name
						FROM (
							SELECT Histories.HistoryID, Histories.TransactionID, 
							Histories.Max(ModificationDate), Histories.TransactionDate,							
							Histories.Description, Statuses.Name AS latest
							FROM Histories INNER JOIN Statuses GROUP BY Histories.TransactionID
						)
						ORDER BY " . $ocol . " " . $odir . "
						LIMIT " . $tstr . ", " . $tfin . ";";
					$histories = mysql_query($sql) or die(mysql_error());
                ?>

                <table class="bordered" id="transaction-list" summary = "List of Transactions">
                    <thead>
                        <td>Transaction ID</td>
                        <td>Transaction Date</td>
                        <td>Description</td>
                        <td>status</td>
                        <td>Amount</td>
                        <td></td>
                    </thead>
                    <tbody>
                        <?php
                            while($row = mysql_fetch_array($histories))
                            {
                        ?>
                            <tr id = >
                                <td><? echo $row['TransactionID']; ?></td>
                                <td><? echo $row['TransactionDate']; ?></td>
                                <td><? echo $row['Description']; ?></td>
                                <td><? echo $row['Status.Name']; ?></td>
                                <td><? echo $latest['Histories.Amount']; ?></td>
                                <td><a href="transaction.php?hid=<? echo $trid; ?>">edit</a></td>
                            </tr>
                        <?php
                            }
                        ?>
                    </tbody>
                </table>
                <div id="pagination"> 
                    <a>Back</a><!-- to do: write this properly in JavaScript - decrements start and submits form-->
					<a>Forward</a><!-- to do: write this properly in JavaScript - increments start and submits form-->
                </ul> -->
            </div><!-- end content -->
        </div><!-- end box -->
        <div id="sidebar">
            <iframe src="/sidebar.html"/>
        </div><!-- end sidebar -->
    </div><!-- end main -->
    </body>
</html>

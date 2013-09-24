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
        <title>Category</title>
        
        <style type="text/css" media="screen">
            @import url("style2.css");
            @import url("styling.css");
        </style>
    </head>

    <body id="main">
        <div id="box">
        <h1>Categories List</h1>
        <form method="post" class="content">        
            <table id="transaction-list" summary = "List of Transactions">
                <thead>
                    <td>Subcateg. ID</td>
                    <td>Categ. ID</td>
                    <td>Name</td>
                    <td>Description</td>
                    <td></td>
                </thead>
                <tbody>
                <?php
                	// Select everything from Category
                	$subCatTable = mysql_query("SELECT * FROM Category ORDER BY CategoryID");
                	// For each row of Category
                	$row = mysql_fetch_array($subCatTable);//////////////////////////////////////////////////////////////////////////// This line contains an error WTF?!
                    ?>
                    <td><?php echo $row['ID']   		?></td>
                    <td><?php echo $row['CategoryID']  	?></td>
                    <td><?php echo $row['Name']      	?></td>
                    <td><?php echo $row['Description']  ?></td>
                ?>
                </tbody>
            </table>> 
        </form>
        
    </div><!-- end box -->
            <div id="sidebar">
            <?php include_once("sidebar.php");?>
        </div>
    </body>
</html>

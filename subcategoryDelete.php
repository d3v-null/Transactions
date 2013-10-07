<!DOCTYPE html>

<html>
	<head>
		<title>Delete Sub Category</title>
		
		<style type="text/css" media="screen">
			@import url("/css/style2.css");
			@import url("/css/styling.css");
		</style>

	</head>

	<body>
		<div id="main">
		
			<div id="box">
				<h1>Delete Sub Category</h1>
					
				<div id="content">

					<?php
					
						// Connect to database
						$connection =mysql_connect("localhost","root","") or die("Could not connect");	
						mysql_select_db("transaction") or die("Unable to select database");
						
						$id = $_GET['subCatID'];
						$subID =  $_GET['id']; 
						
						$sql = "DELETE FROM subcategory WHERE ID= '$id' AND CategoryID='$subID'";
						$result = mysql_query($sql) or die(mysql_error());
						
						// Close conection
						mysql_close($connection);
					?>

					<table class = "formatted">						
						<br><tr>One subcategory has been deleted.</tr><br>						
					</table>
				</div>
				<!-- end content!-->

			</div><!-- end box -->
			
			<div id="sidebar">
		
				<?php include_once("sidebar.php");?>
           
       		</div><!-- end sidebar -->
			
		</div><!-- end main -->   

	</body>
</html>

<!DOCTYPE html>

<html>
<head>
	<title>TAB TITLE</title>
    
    <style type="text/css" media="screen">
        @import url("style2.css");
		@import url("styling.css");
    </style>
	
	<body>
		<div id="main">
		
			<div id="box">
				<h1>Delete Category</h1>

					
				<div id="content">

					<?php
					
						// Connect to database
						$connection =mysql_connect("localhost","root","") or die("Could not connect");	
						mysql_select_db("transaction") or die("Unable to select database");

						$sql = "DELETE FROM category WHERE category.ID ='". $_GET['id']."'";
						$result = mysql_query($sql) or die(mysql_error());
						
					?>	

					<table class = "formatted">						
						<br><tr>One category has been deleted.</tr><br>						
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
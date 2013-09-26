<!DOCTYPE html>

<html>
	<head>
		<title>Create New Category</title>
		
		<style type="text/css" media="screen">
			@import url("style2.css");
			@import url("styling.css");
		</style>

		<script>
		// Validation functions ------ start
		function validateForm(form)
		{
			var error = isEmpty(form.catName) 
				
			if(error != "")
			{
				alert("Some fields need correction: \n" + error);
				return false;
			}
			return true;
		}
		
		function isEmpty(field)
		{
			var error = "";
			
			var value = field.value.trim();
			if(value == "" || value.length==0)
			{
				error = "Please enter a value in '" + field.name + "'\n";
				field.style.background = '#E6CCCC';
			}
			else
			{	
				field.style.background = 'White';
			}
			return error;
		}
		
		function saveAction( )
		{
			var value = document.getElementsByName("catName");
			alert(value.length);
		}
		
		function editAction( )
		{
			alert("to do:");
		}
		
		</script>
			
	</head>

	<body>
		<div id="main">
		
			<div id="box">
				<h1>Create New Category</h1>

					
				<div id="content">

					<table class = "formatted">
						
						<!-- Insert Category Code -->
						<?php
							// Connect to transaction database
							$dbhost = "localhost";
							$dbname = "transaction";
							$dbuser = "root";
							$con    = mysql_connect($dbhost, $dbuser) or die(mysql_error());
							mysql_select_db($dbname) or die(mysql_error());
							
							// Inserting new category
							if (key_exists("catName", $_POST) && key_exists("catDesc", $_POST))
							{
								$catName = $_POST['catName'];
								$description = $_POST['catDesc'];
								
								// Insert values
								$slq="INSERT INTO category (Name, Description) VALUES ('$catName','$description')";
								mysql_query($slq,$con);
								
								echo "Category ". $catName ." was successfully inserted <br><br>";
							}
									
							// Close conection
							mysql_close($con);
						?>

						<form name="categoryForm" action="category.php" method="post">
							<tr>
								<td class = "categoryName">
									Name*:
								</td>
								<td>
									<input type="text" class="data" name="catName" size="50" maxlength="50">
								</td>
							</tr>
							<tr>
								<td class = "categoryDescription">
									Description:
								</td>
								<td>
									<input type="text" class="data" name="catDesc" size="50" maxlength="225">
								</td>
							</tr><tr>* This field is compulsory</tr>
					</table>
					<br>	
						<button input type="submit">Save Category</button>				
						</form>

				</div>
				<!-- end content!-->

			</div><!-- end box -->
			
			<div id="sidebar">
		
				<?php include_once("sidebar.php");?>
           
       		</div><!-- end sidebar -->
			
		</div><!-- end main -->   

	</body>
</html>

<!DOCTYPE html>

<html>
	<head>
		<title>Create New Sub Category</title>
		
		<style type="text/css" media="screen">
			@import url("style2.css");
			@import url("styling.css");
		</style>

		<script>
			// Validation function ------ start
			// http://www.w3schools.com/js/js_form_validation.asp
			function validateForm()
			{
				var error = document.forms["SubCategoryForm"]["subCatName"].value;
				
				if (error == null || error == "")
				{
					alert("First Name must be filled out");
					return false;
				}
			}		
		</script>
	</head>

	<body>
		<div id="main">
		
			<div id="box">
				<h1>Create New Sub Category</h1>
					
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
							if (key_exists("subCatName", $_POST) && key_exists("subCatDesc", $_POST) && key_exists("subCatID", $_POST))
							{
								$catName = $_POST['subCatName'];
								$description = $_POST['subCatDesc'];
								$catID = $_POST['subCatID'];
								
								// Insert values
								$slq="INSERT INTO subcategory (CategoryID, Name, Description) VALUES ('$catID','$catName','$description')";
								mysql_query($slq,$con);
								
								echo "Subcategory ". $catName ." was successfully inserted <br><br>";
							}
									
							// Close conection
							mysql_close($con);
						?>

						<form name="SubCategoryForm" action="subcategory.php?CategoryID=<?php echo $_GET["CategoryID"]; ?>" onsubmit="return validateForm()" method="post">
							<tr>
								<td class = "CategoryName">
									Category ID:
								</td>
								<td>
									<input type="text" class="data" value="<?php echo $_GET["CategoryID"]; ?>" name="subCatID" size="50" maxlength="50" readonly="readonly">
								</td>
							</tr>
							<tr>
								<td class = "SubCategoryName">
									Name*:
								</td>
								<td>
									<input type="text" class="data" name="subCatName" size="50" maxlength="50">
								</td>
							</tr>
							<tr>
								<td class = "SubCategoryDescription">
									Description:
								</td>
								<td>
									<input type="text" class="data" name="subCatDesc" size="50" maxlength="225">
								</td>
							</tr><tr>* This field is compulsory</tr>
					</table>
					<br>	
						<button input type="submit">Save Sub Category</button>				
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

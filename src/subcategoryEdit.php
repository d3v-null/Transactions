<!DOCTYPE html>

<html>
	<head>
		<title>Create New Sub Category</title>
		
		<style type="text/css" media="screen">
			@import url("style2.css");
			@import url("styling.css");
		</style>

		<script>

			// Validation function
			// http://www.w3schools.com/js/js_form_validation.asp
			function validateForm()
			{
				var error = document.forms["SubCategoryForm"]["SubCategoryName"].value;
				
				if (error == null || error == "")
				{
					alert("First Name must be filled out");
					return false;
				}
			}
			
			// Gets all elements with the given class name
			//http://stackoverflow.com/questions/7410949/javascript-document-getelementsbyclassname-compatibility-with-ie
			function setReadonly(classname, bool)
			{
				var regex = new RegExp('(^| )'+classname+'( |$)');
				var elements = document.getElementsByTagName("*");
				var size = elements.length;

				for(var i=0; i < size; i++)
				{
					if(regex.test(elements[i].className))
					{
						if(bool)
						{
							elements[i].setAttribute("readonly","readonly");
						//	elements[i].reset();	// TODO : doesnt work, fixies
						}
						else	
							elements[i].removeAttribute("readonly");
					}
				}
			}
		</script>
	</head>

	<body>
		<div id="main">
		
			<div id="box">
				<h1>Edit Sub Category</h1>
					
				<div id="content">

					<?php
					
						// Connect to database
						$connection =mysql_connect("localhost","root","") or die("Could not connect");	
						mysql_select_db("transaction") or die("Unable to select database");

						$sql = "SELECT * FROM subcategory WHERE ID='" . $_GET['subCatID'] . "' AND CategoryID='" . $_GET['id'] . "'";
						$result = mysql_query($sql) or die(mysql_error());
						$row = mysql_fetch_assoc($result);
											
						// Editing SubCategory
						if (key_exists("SubCategoryName", $_POST) && key_exists("SubCategoryDescription", $_POST) && key_exists("subCatID", $_GET) && key_exists("id", $_GET))
						{
							$catName 		= $_POST['SubCategoryName'];
							$description 	= $_POST['SubCategoryDescription'];
							$catID 			= $_GET['id'];
							$id 			= $_GET['subCatID'];
							
							// Update values
							$sql = "UPDATE subcategory SET Name = '$catName' WHERE subcategory.ID = $id AND subcategory.CategoryID = $catID";
							mysql_query($sql) or die(mysql_error());
							
							$sql = "UPDATE subcategory SET description = '$description' WHERE subcategory.ID = $id AND subcategory.CategoryID = $catID";
							mysql_query($sql) or die(mysql_error());
														
							echo "Subcategory ". $catName ." was successfully edited. <br><br>";
						}
														
						// Close conection
						mysql_close($connection);
					?>

					<table class = "formatted">
						
						<!-- Insert Category Code -->
						<form name="SubCategoryForm" action="" method="post" onsubmit="return validateForm()">
							<!--<tr>
								<td class = "SubCategoryID">
									Subcategory ID:
								</td>
								<td>
									<input type="text" class="data" value="<?php echo $_GET["subCatID"]; ?>" name="subCatID" size="50" maxlength="50" readonly="readonly">
								</td>
							</tr>-->
							<tr>
								<td class = "SubCategoryName">
									Name*:									
								</td>
								<td>
									<input type="text" class="data" value="<?=$row['Name'];?>" name="SubCategoryName" size="50" maxlength="50" readonly="readonly">
								</td>
							</tr>
							<tr><td>Description:</td></tr>
							<tr>
								<td colspan="4" class = "SubCategoryDescription">
									<textarea rows="3" cols="48" class="data" name="SubCategoryDescription" readonly="readonly"><?=$row['Description'];?></textarea>
								</td>							
							</tr><tr>* This field is compulsory</tr>
					</table>	
						<input type="submit" name="SubmitButton" value="Submit" class="button">				
						</form>
						<button onclick="setReadonly('data',false)">Edit</button>
						<button onclick="setReadonly('data',true)">Cancel</button>
						
						<form action="subcategoryDelete.php?id=<?php echo $_GET['id']; ?>&subCatID=<?php echo $_GET['subCatID'];?>">
							<input type=hidden name="id" value="<?php echo $_GET['id']; ?>">
							<input type=hidden name="subCatID" value="<?php echo $_GET['subCatID']; ?>">
							<input type="submit" name="DeleteButton" value="Delete" class="button">
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

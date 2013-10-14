<?php
    require_once 'includes/config.php';
    require_once 'includes/transaction_setup.php';

	$user = new User();

	if (!$user->isTreasurer()){
		echo "<script>alert('You must have treasurer privileges to create a new category. You are going to be redirected to the main page')</script>";
		echo "<meta http-equiv='Refresh' content='0; URL=search.php'>";
    } else {
		// Inserting new category
		if (key_exists("subCatName", $_POST) && key_exists("subCatDesc", $_POST) && key_exists("subCatID", $_POST))
		{
			$catName = $_POST['subCatName'];
			$description = $_POST['subCatDesc'];
			$catID = $_POST['subCatID'];

			// Check for duplicates
			$sql = "SELECT Name FROM subcategory WHERE Name LIKE '$catName'";
			$row = mysql_query($sql);
			$exists = mysql_fetch_array($row);

			if(!$exists){
				// Insert values
				$slq="INSERT INTO subcategory (CategoryID, Name, Description) VALUES ('$catID','$catName','$description')";
				mysql_query($slq);

				echo "<script>alert('Subcategory ". $catName ." was successfully inserted.')</script>";
			} else {
				echo "<script>alert('ERROR: Impossible insert subcategory named as ". $catName ." because it already exists in current database. Please specify other name.')</script>";
			}
		}
	}
?>

<!DOCTYPE html>

<html>
	<head>
		<title>Create New Sub Category</title>

		<style type="text/css" media="screen">
			@import url("css/style2.css");
			@import url("css/styling.css");
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

						<form name="SubCategoryForm" action="subcategoryNew.php?ID=<?php echo $_GET["ID"]; ?>" onsubmit="return validateForm()" method="post">
							<tr>
								<td class = "CategoryName">
									Category ID:
								</td>
								<td>
									<input type="text" class="data" value="<?php echo $_GET["ID"]; ?>" name="subCatID" size="50" maxlength="50" readonly="readonly">
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

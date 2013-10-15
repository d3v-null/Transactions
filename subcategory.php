<?php
    require_once 'includes/transaction_setup.php';
    require_once 'includes/config.php';

    $user = new User();

    if(!$user->loggedIn()){
        redirect('index.php');
    }

    //Has the page been given a category ID?
    $id=(key_exists('id', $_GET)) ? $_GET["id"] : die("No subcategory specified");

    //Does the ID Correspond to a valid subcategory?
    $sql = "SELECT Name, Description FROM subcategory WHERE ID=" . $id . "";
    $result = mysql_query($sql) or die("Subcategory.ID not specified correctly: ".mysql_error());
    if(!$result) die("No subcategories in database match given ID: ".$id);
    $FETCH = mysql_fetch_array($result);

    if(!empty($_POST) && key_exists('delete', $_POST)){
        if (!$user->isTreasurer()){
            echo "<script>alert('You must have treasurer privileges to delete a category. You are going to be redirected to the main page')</script>";
			echo "<meta http-equiv='Refresh' content='0; URL=search.php'>";
        } else {

			$sql = "SELECT HistoryID FROM Categorization WHERE SubCategoryID ='". $_GET['id']."'";
			$result = mysql_query($sql);

			$actionDelete = mysql_fetch_array($result);
			//to do: remove echo "<script>alert('".serialize($actionDelete)."')</script>";

			if($actionDelete['HistoryID'] == FALSE){
				// If this current subcategory isn't associated with transaction
				$sql="DELETE FROM subcategory WHERE subcategory.ID ='". $_GET['id']."'";
				mysql_query($sql) or die("cannot delete category: ".mysql_error());

				redirect("search.php");
			}
			else{
				// Else you can't delete!
				echo "<script>alert('ERROR: It is not possible delete any subcategory which is associated with a transaction.')</script>";
			}
        }
    }

    if(!empty($_POST) && key_exists('save', $_POST)){
		// Check user
		if (!$user->isTreasurer()){
			echo "<script>alert('You must have treasurer privileges to edit a subcategory. You are going to be redirected to the main page')</script>";
			echo "<meta http-equiv='Refresh' content='0; URL=search.php'>";
		} else {

			if(!key_exists('name', $_POST)) {
				echo "<script>alert('No name specified in $_POST')</script>";
			} else if(!key_exists('desc', $_POST)){
				echo "<script>alert('No desc specified in $_POST')</script>";
			} else if($_POST['name'] == ""){
				echo "<script>alert('Name must not be empty')</script>";
			} else If(!$user->isTreasurer()){
				echo "<script>alert('You must have treasurer privileges to modify a category')</script>";
			} else {
				$name = $_POST['name'];
				$desc = $_POST['desc'];
				mysql_query("UPDATE subcategory SET Name='".$name."', Description='".$desc."' ".
							"WHERE subcategory.ID=".$id) or die(mysql_error());
				echo "<script>alert('Successfully updated category')</script>";
			}

			if($_SERVER['HTTP_REFERER']){
				redirect($_SERVER['HTTP_REFERER']);
			} else {
				redirect("search.php");
			}
		}
    }
    //to do: remove echo "id: ".$id." name: ".$name." desc: ".$desc;
?>

<!DOCTYPE html>
<html>
	<head>
        <title>Create New Sub Category</title>
        <link rel="stylesheet" type="text/css" href="css/style2.css">
        <link rel="stylesheet" type="text/css" href="css/styling.css">

		<script>
			// Validation function
			// http://www.w3schools.com/js/js_form_validation.asp
			function validateForm()
			{
				var error = document.forms["subForm"]["name"].value;

				if (error == null || error == "")
				{
					alert("Field 'Name' must be filled out.");
					return false;
				}
			}

		</script>

    </head>

	<body id="main">

			<div id="box">
				<h1>Subcategory Details</h1>

				<form name="subForm" action="subcategory.php?id=<?php echo $id; ?>" onsubmit="return validateForm()" method="post" id="content" >
					<input type="hidden" name="id" value=>

					<b>* This field is compulsory</b>
					<br><br>
					<table class = "formatted">
						<tr>
							<td class = "spaceBelow">Name*:</td>
							<td><input type="text" name="name" class="data" value="<?php echo $FETCH['Name'] ?>"></td>
						</tr>
						<tr>
							<td>Description:</td>
						</tr>
					</table>

					<textarea name="desc" class="data"><?php echo $FETCH['Description'] ?></textarea>
					<br><br>
					<input type="submit" name="delete" 	value="Delete">
					<input type="submit" name="save" 	value="Save">

				</form>

			</div>

        <div id="sidebar">

            <?php include_once("sidebar.php");?>

        </div>

	</body>
</html>

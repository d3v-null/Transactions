<?php
    require_once 'includes/constants.php';
    require_once 'includes/config.php';

    $user = new User();

    if(!$user->loggedIn()){
        redirect('index.php');
    }
    
    // Connect to transaction database
    mysql_connect(DB_SERVER, DB_USER, DB_PASSWORD) or die(mysql_error());
    mysql_select_db(DB_NAME) or die(mysql_error());

    //Has the page been given a category ID?
    $id=(key_exists('id', $_GET)) ? $_GET["id"] : die("No category specified");
    
    //Does the ID Correspond to a valid subcategory?
    $sql = "SELECT Name, Description FROM subcategory WHERE ID=" . $id . "";
    $result = mysql_query($sql) or die("Subcategory.ID not specified correctly: ".mysql_error());
    if(!$result) die("No categories in database match given ID: ".mysql_error());
    $row = mysql_fetch_array($result);
    
    $name = $row['Name'];
    $desc = $row['Description'];   
    
    if(!empty($_POST) && key_exists('save', $_POST)){
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
            //echo "<script>alert('Successfully updated category')</script>";
        }
    }
	else if(!empty($_POST) && key_exists('delete', $_POST)){
        if (!$user->isTreasurer()){
            echo "<script>alert('You must have treasurer privileges to delete a category')</script>";
        } else {
			
			$sql = "SELECT id FROM Categorization WHERE SubCategoryID ='". $_GET['id']."'";
			ECHO "<br><br><br><br><br>fadfafsaffasfasfadf</br></br></br></br></br>";
			$actionDelete = mysql_fetch_array($sql,MYSQL_NUM);
					
			if($actionDelete['id'] == FALSE){
				// If this current subcategory isn't associated with transaction
				$sql="DELETE FROM subcategory WHERE subcategory.ID ='". $_GET['id']."'";
				mysql_query($sql) or die("cannot delete category: ".mysql_error());
			}
			else{
				// Else you can't delete!
				echo "<script>alert('ERROR: This subcategory has transactions associated with.')</script>";
			}
        }
        $redirect = False;
        if($redirect && $_SERVER['HTTP_REFERER']){
            redirect($_SERVER['HTTP_REFERER']);
        } else {
            redirect("search.php");
        }
    }
    //echo "id: ".$id." name: ".$name." desc: ".$desc;
?>

<!DOCTYPE html>
<html>
	<head>
        <title>Create New Sub Category</title>
        <link rel="stylesheet" type="text/css" href="/css/style2.css">
        <link rel="stylesheet" type="text/css" href="/css/styling.css">
		
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
			
		</script>
		
    </head>	

	<body>
		<div id="main">
		
			<div id="box">
				<h1>Subcategory Details</h1>
					
				<form action="subcategory.php?id=<?php echo $id; ?>" method="post" id="content" >
					<input type="hidden" name="id" value=>
					
					<b>* This field is compulsory</b>
					<table class = "formatted">						
						<tr class = "spaceBelow">
							<td>Name*:</td>
							<td><input type="text" name="name" class="data" value="<?php echo $name ?>"></td>							
						</tr>
						<tr class = "spaceBelow">
							<td>Description:</td>
						</tr>						
					</table>  
					
					<textarea name="desc" class="data"><?php echo $desc ?></textarea>					
					
					<input type="submit" name="delete" 	value="Delete">
					<input type="submit" name="save" 	value="Save">
                
				</form>	

			</div>
			
			<div id="sidebar">
		
				<?php include_once("sidebar.php");?>
           
       		</div>
			
		</div>

	</body>
</html>

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
    $result = mysql_query($sql) or die("Category.ID not specified correctly: ".mysql_error());
    if(!$result) die("No categories in database match given ID: ".mysql_error());
    $row = mysql_fetch_array($result);
    
    $name = $row['Name'];
    $desc = $row['Description'];   
    
    if(!empty($_POST)){
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
                        "WHERE category.ID=".$id) or die(mysql_error());
            //echo "<script>alert('Successfully updated category')</script>";
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
			
			// Gets all elements with the given class name
			//http://stackoverflow.com/questions/7410949/javascript-document-getelementsbyclassname-compatibility-with-ie
			//function setReadonly(classname, bool)
			//{
			//	var regex = new RegExp('(^| )'+classname+'( |$)');
			//	var elements = document.getElementsByTagName("*");
			//	var size = elements.length;

			//	for(var i=0; i < size; i++)
			//	{
			//		if(regex.test(elements[i].className))
			//		{
			//			if(bool)
			//			{
			//				elements[i].setAttribute("readonly","readonly");
			//			//	elements[i].reset();	// TODO : doesnt work, fixies
			//			}
			//			else	
			//				elements[i].removeAttribute("readonly");
			//		}
			//	}
			//}
			
		</script>
		
    </head>	

	<body>
		<div id="main">
		
			<div id="box">
				<h1>Edit Sub Category</h1>
					
				<div id="content">

					<table class = "formatted">
						
						<form name="SubCategoryForm" action="" method="post" onsubmit="return validateForm()">
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
						
						<form action="subcategoryDelete.php?id=<?php echo $id ?>">
							<input type=hidden name="id" value="<?php echo $id ?>">	
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

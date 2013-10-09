<?php
    $page_title  ='Category Details';
    require_once 'includes/transaction_setup.php';
    require_once 'includes/config.php';

    $user = new User();

    if(!$user->loggedIn()){
        redirect('index.php');
    }

    //Has the page been given a category ID?
    $id=(key_exists('id', $_GET)) ? $_GET["id"] : die("No category specified");

    //Does the ID Correspond to a valid category?
    $sql = "SELECT Name, Description FROM category WHERE ID=".$id;
    $result = mysql_query($sql) or die("Category.ID not specified correctly: ".mysql_error());
    if(!$result) die("No categories in database match given ID: ".mysql_error());
    $row = mysql_fetch_array($result);

    $name = $row['Name'];
    $desc = $row['Description']; 
    
    //If the save button was pressed
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
            mysql_query("UPDATE category SET Name='".$name."', Description='".$desc."' ".
                        "WHERE category.ID=".$id) or die(mysql_error());
            //echo "<script>alert('Successfully updated category')</script>";
        }
    } else if(!empty($_POST) && key_exists('delete', $_POST)){
        if (!$user->isTreasurer()){
            echo "<script>alert('You must have treasurer privileges to delete a category')</script>";
        } else {
            $sql="DELETE FROM category WHERE category.ID ='". $_GET['id']."'";
            mysql_query($sql) or die("cannot delete category: ".mysql_error());
        }
        $redirect = False;
        if($redirect && $_SERVER['HTTP_REFERER']){
            redirect($_SERVER['HTTP_REFERER']);
        } else {
            redirect("search.php");
        }
    }
    
    //echo "id: ".$id." name: ".$name." desc: ".$desc." row: ".$row;
?>

<!DOCTYPE html>
<html>
    <head>
        <title><?php echo $page_title ?></title>
        <link rel="stylesheet" type="text/css" href="/css/style2.css">
        <link rel="stylesheet" type="text/css" href="/css/styling.css">
    </head>
	
    <body id="main">
		<div id="box">
			<?php include 'subheader.php' ?>
            
			<form action="category.php?id=<?php echo $id; ?>" method="post" id="content" >
                <input type="hidden" name="id" value=>
                
                <b>* This field is compulsory</b>
                <table class = "formatted">						
                    <tr class = "spaceBelow">
                        <td>Name*:</td>
                        <td><input type="text" name="name" class="data" value="<?php echo $name ?>"></td>							
                    </tr>
                    <tr class = "spaceBelow">
                        <td>Description:</td>
                        <td></td>							
                    </tr>    
                </table>
                <textarea name="desc" class="data"><?php echo $desc ?></textarea>
                
                
                <input type="submit" name="save" value="Save">
                <input type="submit" name="delete" value="Delete">
                
            </form> 
            <!--<form action="categoryDelete.php" method="get">
                <input type="hidden" name="id" value="<?php echo $id ?>">
                <input type="submit" value="Delete">
            </form>
            <button onclick="location.href='categoryDelete.php?id=<?php echo $id ?>'">Delete</button> -->
        </div><!-- end box-->
        <div id="sidebar">      
				<?php include_once("sidebar.php");?>                 
        </div><!-- end sidebar -->      
	</body>
    
</html>


<?php
    require_once 'includes/constants.php';
    require_once 'includes/config.php';

    $user = new User();

    if(!$user->loggedIn()){
        redirect('index.php');
    }

?>

<!DOCTYPE html>
<html>
    <?php
        // Connect to transaction database

		mysql_connect(DB_SERVER, DB_USER, DB_PASSWORD) or die(mysql_error());
		mysql_select_db(DB_NAME) or die(mysql_error());
    ?>

    <head>
        <title>Category Details</title>
        
        <link rel="stylesheet" type="text/css" href="/css/style2.css">
        <link rel="stylesheet" type="text/css" href="/css/styling.css">
        
		<script>
			// Validation function ------ start
			// http://www.w3schools.com/js/js_form_validation.asp
			function validateForm()
			{
				var name = document.forms["categoryForm"]["Name"].value;
				
				if (name == null || name == "")
				{
					alert("First Name must be filled out");
					return false;
				}
			}			
			// validate function  ----- end			
        </script>
    </head>
	
    <body id="main">
        
		<?php
            $id=(key_exists('id', $_GET)) ? $_GET["id"] : die("No category specified");
            
            $sql = "SELECT * FROM category WHERE ID=" . $id . "";
            $result = mysql_query($sql) or die(mysql_error());
            $row = mysql_fetch_assoc($result);	
            
            $name = $row['Name'];
            $desc = $row['Description'];    
            
            if(!empty($_POST)){
                //Override 
                if( key_exists('name', $_POST) ) {
                    $name = $_POST['name'];
                }
                if( key_exists('desc', $_POST) ) {
                    $desc = $_POST['desc'];
                }
                if($name = ""){
                    echo "<script>alert('Name cannot be empty')</script>";
                } else If(!$user->isTreasurer()){
                    echo "<script>alert('Cannot update category without Treasurer privileges')</script>";
                } else {
                    $sql = "UPDATE category SET Name = ".$name.", Description = ".$desc." WHERE category.ID = $id";
                    mysql_query($sql) or die(mysql_error());
                    echo "<script>alert('Successfully updated category')</script>";
                }
            }
        ?>
		
		<div id="box">
			<h1>Category Details</h1>
			<form name="categoryForm"  id="content" action="category.php?id=<?php $id?>" method="post">
            <input type="hidden" name="id" value=>
            
            <b>* This field is compulsory</b>
            <table class = "formatted">						
                <tr class = "spaceBelow">
                    <td>Name*:</td>
                    <td><input type="text" name="name" class="data" value="<?php $row['Name'] ?>"></td>							
                </tr>
                <tr class = "spaceBelow">
                    <td>Description:</td>
                    <td><textarea name="desc" class="data"><?php $row['Description'] ?></textarea></td>							
                </tr>    
            </table>
            
            <input type="submit" value="Save">
            <form action="categoryDelete.php" method="get">
                <input type="hidden" name="id" value="<?php echo $id ?>">
                <input type="submit" value="Delete">
            </form>
        </form>     
        
        <div id="sidebar">      
				<?php include_once("sidebar.php");?>                 
        </div><!-- end sidebar -->      
	</body>
</html>

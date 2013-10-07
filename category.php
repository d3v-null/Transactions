
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
        <title>TAB TITLE</title>
        
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
                if( key_exists('name', $_POST) ) {
                    $name = $_POST['name'];
                }
                if( key_exists('desc', $_POST) ) {
                    $desc = $_POST['desc'];
                }
                If($user->isTreasurer()){
                    $sql = "UPDATE category SET Name = ".$name.", Description = ".$desc." WHERE category.ID = $id";
                    mysql_query($sql) or die(mysql_error());
                    
                } else {
                    die("Cannot update category without Treasurer privileges");
                }
            }
            
            //check 
        ?>
		
		<div id="box">
            
			<h1>Category Details</h1>
            
			<form name="categoryForm"  id="content" action="category.php" onsubmit="return validateForm()" method="post">
            
            <?php
                $sql = "SELECT * FROM category WHERE ID='" . $id . "'";
                $result = mysql_query($sql) or die(mysql_error());
                $row = mysql_fetch_assoc($result);				
                
                // Editing Category
                if (key_exists("Name", $_POST) && key_exists("Description", $_POST) && key_exists("id", $_GET)) // Submit was clicked
                {
                    $name 			= $_POST['Name'];
                    $description 	= $_POST['Description'];
                    $id				= $_GET['id'];
                    
                    // Update Values
                    $sql = "UPDATE category SET Name = '$name' WHERE category.ID = $id";
                    mysql_query($sql) or die(mysql_error());
                    
                    $sql = "UPDATE category SET Description = '$description' WHERE category.ID = $id";
                    mysql_query($sql) or die(mysql_error());
                                                
                    echo "Category ".$_POST['Name']." was updated.";
                }           
            ?>	
			
            <input type="hidden" name="id">
            
            <b>* This field is compulsory</b>
            <table class = "formatted">						
                <tr class = "spaceBelow">
                    <td>
                        Name*: 
                    </td>
                    <td>
                        <input type="text" class="data" name="Name" value="<?=$row['Name'];?>" readonly="readonly">
                    </td>							
                </tr>
                
                <tr class = "spaceBelow">
                    <td>
                        Description: 
                    </td>
                    <td>
                        <textarea class="data" name="Description" readonly="readonly"><?=$row['Description'];?></textarea>
                    </td>							
                </tr>    
            </table>
            
            <input type="submit" name="SubmitButton" value="Save" class="button">
            <form action="categoryDelete.php" method="get">
                <input type="hidden" name="id" value="<?php echo $id ?>">
                <input type="submit" name="DeleteButton" value="Delete" class="button">
            </form>
        </form>     
        
        <div id="sidebar">      
				<?php include_once("sidebar.php");?>                 
        </div><!-- end sidebar -->      
	</body>
</html>

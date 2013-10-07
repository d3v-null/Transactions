<!DOCTYPE html>
<?php
    require_once 'includes/constants.php';
?>

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
				var error = document.forms["transactionForm"]["Name"].value;
				
				if (error == null || error == "")
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
            $id=(key_exists("id", $_GET)) ? $_GET["id"] : Null;
        ?>
		<div id="box">
            <h1>Category Details</h1>
            <form name="transactionForm"  id="content" action="categoryEdit.php" onsubmit="return validateForm()" method="get">
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
            <table class = "formatted">						
                <br><tr>* This field is compulsory</tr><br>
                <tr>
                    <td colspan="4" class = "spaceBelow">
                        Name*: 
                        <input type="text" class="data" name="Name" size="55"  value="<?=$row['Name'];?>" readonly="readonly">
                    </td>							
                </tr>
                <tr><td>Description:</td></tr>
                <tr>
                    <td colspan="4" class = "spaceBelow">
                        <textarea rows="3" cols="48" class="data" name="Description" readonly="readonly"><?=$row['Description'];?></textarea>
                    </td>							
                </tr>
            </table>
            <input type="submit" name="SubmitButton" value="Submit" class="button">
            </form>
            <button onclick="setReadonly('data',false)">Edit</button>
            <button onclick="setReadonly('data',true)">Cancel</button>
                                
            <form action="categoryDelete.php?id=<?php echo $_GET['id']; ?>">
                <input type="hidden" name="id" value="<?php echo $_GET['id']; ?>">
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

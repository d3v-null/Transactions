<!DOCTYPE html>
<?php
$connection =mysql_connect("localhost","roo","") or die("Could not connect");

mysql_select_db("test") or die("Unable to select database");
?>

<html>
<head>
	<title>Edit Category</title>
    
    <style type="text/css" media="screen">
        @import url("style2.css");
		@import url("styling.css");
    </style>

<script>

			// Validation functions ------ start
			function validateForm(form)
			{
				var error = isEmpty(form.Name);
					
				if(error != "")
				{
					alert("Some fields need correction: \n" + error);
					return false;
				}
				return true;
			}
			
			function isEmpty(field)
			{
				var error = "";
				
				var value = field.value.trim();
				if(value == "" || value.length==0)
				{
					error = "Please enter a value in '" + field.name + "'\n";
					field.style.background = '#E6CCCC';
				}
				else
				{	
					field.style.background = 'White';
				}
				return error;
			}
			
			function validateInt(field)
			{
				var error = "";
				
				if((error =isEmpty(field)) == "")
				{
					var value = field.value;
					var stripped = value;//fieldVal.replace(/$/g,"");
					//document.write(stripped);
					if(isNaN(parseInt(stripped)))	// TODO: check for special chars
					{
						error = "Invalid characters in '" + field.name + "'\n";
						field.style.background = '#E6CCCC';
					}
					else
					{
						field.style.background = 'White';
					}
				}
				return error;
			}
			
			// validate functions ----- end
			
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
	
	<body>
		<div id="main">
		
			<div id="box">
				<h1>Edit Category</h1>

					
				<div id="content">

					<?php
					
						// Connect to database
						$connection =mysql_connect("localhost","root","") or die("Could not connect");	
						mysql_select_db("transaction") or die("Unable to select database");

						$sql = "SELECT * FROM category WHERE ID='" . $_GET['id'] . "'";
						$result = mysql_query($sql) or die(mysql_error());
						$row = mysql_fetch_assoc($result);				
						
						if (key_exists("Name", $_POST) && key_exists("Description", $_POST) && key_exists("id", $_GET)) // Submit was clicked
					   	{
					   		$name 			= $_POST['Name'];
							$description 	= $_POST['Description'];
							$id				= $_GET['id'];
							
					   		$sql = "UPDATE category SET Name = '$name' WHERE category.ID = $id";
							mysql_query($sql) or die(mysql_error());
							
							$sql = "UPDATE category SET Description = '$description' WHERE category.ID = $id";
							mysql_query($sql) or die(mysql_error());
														
							echo "Category ".$_POST['Name']." was updated.";
					    }
					?>	

					<table class = "formatted">

						<form name="transactionForm" action="categoryEdit.php?id=<?php echo $_GET['id']; ?>" method="post">
						<tr>
							<td colspan="4" class = "spaceBelow">
								Name: 
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
						<button onclick="deleteTable('data',true)">Delete</button>
	
		</div>
  	
				<!-- end content!-->
            </div><!-- end box -->
            
            <div id="sidebar">
            
				<?php include_once("sidebar.php");?>           
                
            </div><!-- end sidebar -->
            
            
        </div><!-- end main -->
           
</body>
</html>

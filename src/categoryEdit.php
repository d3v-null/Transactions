<!DOCTYPE html>

<html>
<head>
	<title>TAB TITLE</title>
    
    <style type="text/css" media="screen">
        @import url("style2.css");
		@import url("styling.css");
    </style>

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

					<table class = "formatted">						
						<br><tr>* This field is compulsory</tr><br>
						<form name="transactionForm" action="categoryEdit.php?id=<?php echo $_GET['id']; ?>" onsubmit="return validateForm()" method="post">
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
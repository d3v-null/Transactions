<!DOCTYPE html>

<html>
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
			// validate functions ----- end
			
			function deleteRowAlert()
			{
				alert("First Name must be filled out");
			}
			
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
			
			// http://stackoverflow.com/questions/1586330/access-get-directly-from-javascript
			function deleteAction() {
			      
		      	var parts = window.location.search.substr(1).split("&");
				var $_GET = {};
				for (var i = 0; i < parts.length; i++) {
				    var temp = parts[i].split("=");
				    $_GET[decodeURIComponent(temp[0])] = decodeURIComponent(temp[1]);
				}
				
				// TO DO: TEST
				//var db = openDatabase();
				
			    //
			    var db = new ActiveXObject ("localhost.Connection");	
			    db.Open("SELECT FROM category WHERE category.ID = $_GET['id']");	
			    db.Delete;
			    db.Close;	   	
			    //var service = db.ConnectServer(".","root","");
				//alert($_GET['id']);
			    //var properties = service.ExecQuery("DELETE FROM category WHERE category.ID = $_GET['id']");
			    			    
			}
	</script>	
    
    <?php
        $id=(key_exists("id", $_GET)) ? $_GET["id"] : Null;
    ?>
</head>
	<body>
		<div id="main">
		
			<div id="box">
				<h1>Edit Category</h1>

					
				<div id="content">

					<?php
					
						// Connect to database
						$connection = mysql_connect("localhost","root","") or die("Could not connect");	
						mysql_select_db("transaction") or die("Unable to select database");

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
                    <form name="transactionForm" action="categoryEdit.php" onsubmit="return validateForm()" method="post">
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
						<input type=hidden name="id" value="<?php echo $_GET['id']; ?>">
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

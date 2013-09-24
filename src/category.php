<!DOCTYPE html>

<html>
	<head>
		<title>Create New Category</title>
		
		<style type="text/css" media="screen">
			@import url("style2.css");
			@import url("styling.css");
		</style>

		<script>
		// Validation functions ------ start
		function validateForm(form)
		{
			var error = isEmpty(form.catName) 
				
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
		
		function saveAction( )
		{
			alert("to do: saveAction");
		}
		
		function editAction( )
		{
			alert("to do:");
		}
		
		</script>
	</head>

	<body>
		<div id="main">
		
			<div id="box">
				<h1>Create New Category</h1>

					
				<div id="content">

					<?php
					
						// Connet to database
						$connection =mysql_connect("localhost","root","") or die("Could not connect");	
						mysql_select_db("transaction") or die("Unable to select database!!");
						
						// Defining variables
						$catName = "";						
					?>	

					<table class = "formatted">
						<!-- action="toMe.php" -->

						<form name="categoryForm"  method="get">
							<tr>
								<td class = "categoryName">
									Name*:
								</td>
								<td>
									<input type="text" class="data" name="catName" size="50" value=<?php echo $catName?>>
								</td>
							</tr>
							<tr>
								<td class = "categoryDescription">
									Description:
								</td>
								<td>
									<input type="text" class="data" name="desc" size="50">
								</td>
							</tr><tr>* This field is compulsory</tr>
					</table>
					<br>					
						</form>
							<button input type="submit" onclick="saveAction()">Save Category</button>
							<button onclick="deleteAction()">Delete Category</button>
				</div>
				<!-- end content!-->

			</div><!-- end box -->
			
			<div id="sidebar">
			
				<!-- to do: load sidebar -->

			</div><!-- end sidebar -->
			
		</div><!-- end main -->   

	</body>
</html>

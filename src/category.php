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
			var value = document.getElementsByName("catName");
			alert(value.length);
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

					<table class = "formatted">

						<form name="categoryForm" action="search.php" method="post">
							<tr>
								<td class = "categoryName">
									Name*:
								</td>
								<td>
									<input type="text" class="data" name="catName" size="50" maxlength="50">
								</td>
							</tr>
							<tr>
								<td class = "categoryDescription">
									Description:
								</td>
								<td>
									<input type="text" class="data" name="catDesc" size="50" maxlength="225">
								</td>
							</tr><tr>* This field is compulsory</tr>
					</table>
					<br>	
						<button input type="submit">Save Category</button>				
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

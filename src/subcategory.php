<!DOCTYPE html>

<html>
	<head>
		<title>Create New Subcategory</title>
		
		<style type="text/css" media="screen">
			@import url("style2.css");
			@import url("styling.css");
		</style>

		<script>
		function saveAction( )
		{
			// to do: save action
		}
		
		function editAction( )
		{
			// to do: edit action
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
												
					?>	

					<table class = "formatted">
						<!-- action="toMe.php" -->

						<form name="categoryForm"  action="toMe.php" method="post">
							<tr>
								<td class = "categoryName">
									Name*:
								</td>
								<td>
									<input type="text" class="data" name="Date_trans" size="50" value="">
								</td>
							</tr>
							<tr>
								<td class = "categoryDescription">
									Description:
								</td>
								<td>
									<input type="text" class="data" name="Amount" size="50">
								</td>
							</tr><tr>* This field is compulsory</tr>
					</table>
					<br>					
						</form>
							<button onclick="saveAction()">Save Category</button>
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

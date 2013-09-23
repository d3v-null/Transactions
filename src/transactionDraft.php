<!DOCTYPE html>

<html>
	<head>
		<title>TAB TITLE</title>
		
		<style type="text/css" media="screen">
			@import url("style2.css");
			@import url("styling.css");
		</style>

		<script>

			// Validation functions ------ start
			function validateForm(form)
			{
				var error = "";
				error  += isEmpty(form.TransactionName) 
				+ isEmpty(form.Description)
				+ isEmpty(form.Date_trans)
				+ isEmpty(form.Date_payRec)
				+ isEmpty(form.Resp)
				+ validateInt(form.Amount)
				+ validateRadio("Type");
					
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
			
			function validateRadio(id)
			{
				var error = "";
				var radios = document.getElementsByName(id);
				var valid = false;
				var size = radios.length;
				for(var i=0; i< size; i++)
				{
					if(radios[i].checked)
						valid = true;			
				}
				if(!valid)
					error = "Please select an option for '" + id + "'\n";
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
							elements[i].setAttribute("readonly","readonly");
						else	
							elements[i].removeAttribute("readonly");
					}
				}
			}

		</script>	
	</head>

	<body>
		<div id="main">
		
			<div id="box">
				<h1>Transaction Details</h1>

					
				<div id="content">

					<?php
					
						// Connet to database
						$connection =mysql_connect("localhost","test","test") or die("Could not connect");	
						mysql_select_db("test") or die("Unable to select database");

						$sql = "SELECT * FROM Tester WHERE PID='" . $_GET['id'] . "'";
						$result = mysql_query($sql) or die(mysql_error());
						$row = mysql_fetch_assoc($result);
					?>	

					<table class = "formatted">
						<!-- action="toMe.php" -->

						<form name="transactionForm"  onsubmit="return validateForm(this)" action="toMe.php" method="post">
							<tr>
								<td class = "transactionTitle">
									Transaction Name*:
								</td>
								<td>
									<input type="text" class="data" name="TransactionName" value="<?=$row['TransactionName'];?>" readonly="readonly">
								</td>
								<td class = "transactionTitle">
									Status*:
								</td>
								<td>
									<select>
										<option value="pending">Pending</option>
										<option value="complete">Complete</option>
										<!-- selected="selected" -->
									</select>
								</td>
							</tr>
							<tr>
								<td class = "transactionTitle">
									Transaction Date*:
								</td>
								<td>
									<input type="datetime" class="data" name="Date_trans" size="8" value="" readonly="readonly">
								</td>
								<td class = "transactionTitle">
									Amount*:
								</td>
								<td>
									<input type="text" class="data" name="Amount" size="8"  value="<?=$row['Amount'];?>" readonly="readonly">
								</td>
							</tr>
							<tr>
								<td class = "transactionTitle">
									Date of receipt/payment*:
								</td>
								<td>
									<input type="datetime" class="data" name="Date_payRec" size="8" readonly="readonly">
								</td>
								<td class = "transactionTitle">
									Type*:
								</td>
								<td>
							
									<input type="radio" class="data" name="Type" value="in">Inflow <br>
									<input type="radio" class="data" name="Type" value="out">Outflow<br>
								</td>
							</tr>
							<tr>
								<td class = "transactionTitle">
									Responsible*:
								</td>
								<td>
									<input type="text" class="data" name="Resp" size="12" readonly="readonly">
								</td>
							</tr>
							<tr>
								<td class = "transactionTitle spaceBelow">
									Associated person:
								</td>
								<td>
									<input type="text" class="data" name="assoc" size="12" readonly="readonly">
								</td>
							</tr>
							<tr>
								<td class = "transactionTitle">
									Description*:
								</td>
								<td>
									<textarea cols="20" class="data" name="Description" readonly="readonly"><?=$row['Description'];?></textarea>
								</td>
							</tr>
							<tr>
							<tr>
								<td class = "transactionTitle spaceBelow">
									Comment:
								</td>
								<td>
									<textarea cols="20" class="data" name="Comment" readonly="readonly"></textarea>
								</td>
							</tr>
					</table>
<!--						<button type="Reset">Clear</button>
 -->						<input type="submit" value="Submit" class="button">
							
						</form>
							<button onclick="setReadonly('data',false)">Edit</button>
							<button onclick="setReadonly('data',true)">Cancel</button>
				</div>


				<!-- end content!-->
				
	   
			</div><!-- end box -->
			
			<div id="sidebar">
			
				<h2>Random side bar info</h2>
				<p>	Stuff
				</p>
				<ul>
					<li>Point 1</li>
					<li>Point 2</li>
				</ul>

			</div><!-- end sidebar -->
			
		</div><!-- end main -->   

	</body>
</html>

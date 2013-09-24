<!DOCTYPE html>
<?php
$connection =mysql_connect("localhost","test","test") or die("Could not connect");

mysql_select_db("test") or die("Unable to select database");
?>

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
				error  += isEmpty(form.Description) 
				+ isEmpty(form.ResponsibleParty)
				+ isEmpty(form.AssociatedParty)
				+ isEmpty(form.PaymentDate)
				+ isEmpty(form.TransactionDate)
				+ isEmpty(form.Comment)
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
						{
							elements[i].setAttribute("readonly","readonly");
						//	elements[i].reset();	// TODO : doesnt work, fixies
						}
						else	
							elements[i].removeAttribute("readonly");
					}
				}
			}
			

			<?php    
				if(isset($_POST['SubmitButton']))
				{
				echo("ERERER");

				$sql = "INSERT INTO Persons (FirstName) VALUES ('$input')";
										$sql = "INSERT INTO Transaction (Description, Comment, TransactionDate, PaymentDate, ResponsibleParty, AssociatedParty, Amount) VALUES ('$_POST['Description']',
										'$_POST['Comment']',
										'$_POST['TransactionDate']',
										'$_POST['PaymentDate']',
										'$_POST['ResponsibleParty']',
										'$_POST['AssociatedParty']',
										'$_POST['Amount']');";
					mysql_query($sql) or die(mysql_error());
				} 
				//TODO else	
			?> 
		</script>	
	
	<body>
		<div id="main">
		
			<div id="box">
				<h1>Transaction Details</h1>

					
				<div id="content">

					<?php
					
						// Connect to database
						$connection =mysql_connect("localhost","test","test") or die("Could not connect");	
						mysql_select_db("test") or die("Unable to select database");

						$sql = "SELECT * FROM Tester WHERE PID='" . $_GET['id'] . "'";
						$result = mysql_query($sql) or die(mysql_error());
						$row = mysql_fetch_assoc($result);
					?>	

					<table class = "formatted">
						<!-- action="toMe.php" -->

						<form name="transactionForm"   action="" method="post">
						<tr>
							<td  colspan = "2" class = "transactionTitle">
								Transaction Description
							</td>
							<td></td>
							<td>
								<select>
									<option value="" selected="selected"></option>
									<option value="pending">Pending</option>
									<option value="complete">Complete</option>
								</select>
							</td>
						</tr>
						<tr>
							<td colspan="4" class = "spaceBelow">
								<textarea class="data" name="Description" readonly="readonly"></textarea>
							</td>
							
						</tr>
						<tr>
							<td class = "transactionTitle">
								Transaction Date*:
							</td>
							<td>
								<input type="datetime" class="data" name="TransactionDate" size="12" value="" readonly="readonly">
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
								<input type="datetime" class="data" name="PaymentDate" size="12" readonly="readonly">
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
								<input type="text" class="data" name="ResponsibleParty" size="12" readonly="readonly">
							</td>
						</tr>
						<tr>
							<td class = "transactionTitle spaceBelow">
								Associated person:
							</td>
							<td>
								<input type="text" class="data" name="AssociatedParty" size="12" readonly="readonly">
							</td>
						</tr>
						<tr>
						<tr>
							<td class = "transactionTitle">
								Comment:
							</td>
						</tr>
						<tr>
							<td colspan = "2">
								<textarea cols="20" class="data" name="Comment" readonly="readonly"><?=$row['Description'];?></textarea>
							</td>
						</tr>
				</table>
						<button type="Reset">Clear</button>
						<input type="submit" name="SubmitButton" value="Submit" class="button">

					</form>
						<button onclick="setReadonly('data',false)">Edit</button>
						<button onclick="setReadonly('data',true)">Cancel</button>
					
							

						
						
						
				     		<?php
			$sql="SELECT * FROM Persons";
			$result =   mysql_query($sql) or die(mysql_error());
			echo "<table border='1'>";

			while($row = mysql_fetch_assoc($result))
			{
				echo "<tr>";
				foreach($row as $cname => $cvalue)
				{
					echo "<td>" ;
					print "$cname:  $cvalue\t";
					echo "</td>";
				}
				echo"</tr>";
				}
			echo "</table>"; 
			return mysql_num_rows($result);

		
		
		?>
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

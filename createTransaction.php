<!DOCTYPE html>
<?php
$connection =mysql_connect("localhost","test","test") or die("Could not connect");
// TODO : escape '\' in comment or description field
mysql_select_db("transaction") or die("Unable to select database");
?>

<html>
<head>
	<title>TAB TITLE</title>
    
    <style type="text/css" media="screen">
      @import url("css/style2.css");
      @import url("css/styling.css");
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
				+ validateInt(form.Amount)
				+ validateDropdown("Status")
				+ validateRadio("Type");
					
				if(error != "")
				{
					alert("Some fields need correction: \n" + error);
					return false;
				}
        else
        {
          alert("Transaction successfully created!\nReturning to home page...");
          return true;
        }
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
			
			function validateDropdown(id)
			{	
				var error="";
				var elem = document.getElementById(id);
				if(elem.selectedIndex == 0)
				{
					error = "Please select an option for '" + id + "'\n";
				}
				return error;
			}
			

						function validateInt(field)
			{
				var error = "";
				
				if((error =isEmpty(field)) == "")
				{
					var value = field.value;
					if(isNaN(value))	// TODO: check for special chars
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
      
		</script>	
    
		<?php    
    
      // remove single and double quotes so no errors are thrown with the sql
      function removeQuotes($string)
      {
        $string = str_replace("'","\'", $string);
        return str_replace("\"", "\\\"", $string);
      }
      
      
			if(isset($_POST['submitButton']))
			{
        $transactionIDresult = mysql_query("SELECT MAX(TransactionID)+1 FROM History");
        $transactionID = preg_match_all('!\d+!', (string)$transactionIDresult);
        echo "$transactionID";
				$sql = "INSERT INTO History  (TransactionID, Description, TransactionDate, Amount, PaymentDate, ResponsibleParty, AssociatedParty, Inflow, StatusID, Comment)" .
           "VALUES (".
					"'$transactionID', ".
					"'" . removeQuotes($_POST['Description']) . "', ".
					"'" . $_POST['TransactionDate'] . "', ".
					"'" . $_POST['Amount'] . "', ".
					"'" . $_POST['PaymentDate'] . "', ".
					"'" . $_POST['ResponsibleParty'] . "', ".
					"'" . $_POST['AssociatedParty'] . "', ".
					"'" . ($_POST['Type']=="in") . "', ".
				  "'" . $_POST['Status'] . "', ".
					"'" . removeQuotes($_POST['Comment']) . "')";
          echo " > > > $sql";
				mysql_query($sql) or die(mysql_error());
        
        header( 'Location:http://localhost:81/search.php');
        exit();
			} 

			//TODO else	
		?>  
	
	<body onload="initialiseTabs()">
		<div id="main">
		
			<div id="box">
				<h1>Transaction Details</h1>

				<div id="content">
            
            <table class = "formatted">
              <!-- action="toMe.php" -->

              <form name="transactionForm" onsubmit="return validateForm(this);" action="" method="post">
              <tr>
                <td  colspan = "2" class = "transactionTitle">
                  Transaction Description
                </td>
                <td></td>
                <td>
                  <select id="Status" name = "Status">
                    <option value="" selected="selected"></option>
                    <?php
                      $sql = "SELECT * FROM Status";
                      $statusIDs = mysql_query($sql, $connection) or die(mysql_error());
                      while($row = mysql_fetch_array($statusIDs))
                      {
                        echo "<option value=" . $row['ID'] . ">" . $row['Name'] . "</option>";
                      }
                    ?>
                  </select>
                </td>
              </tr>
              <tr>
                <td colspan="4" class = "spaceBelow">
                  <textarea class="data" name="Description" ></textarea>
                </td>
                
              </tr>
              <tr>
                <td class = "transactionTitle">
                  Transaction Date*:
                </td>
                <td>
                  <input type="datetime" class="data" name="TransactionDate" size="12">
                </td>
                <td class = "transactionTitle col2">
                  Amount*:
                </td>
                <td>
                  <input type="text" class="data" name="Amount" size="8">
                </td>
              </tr>
              <tr>
                <td class = "transactionTitle">
                  Date of receipt/payment*:
                </td>
                <td>
                  <input type="datetime" class="data" name="PaymentDate" size="12" >
                </td>
                <td class = "transactionTitle col2">
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
                  <input type="text" class="data" name="ResponsibleParty" size="12" >
                </td>
              </tr>
              <tr>
                <td class = "transactionTitle spaceBelow">
                  Associated person:
                </td>
                <td>
                  <input type="text" class="data" name="AssociatedParty" size="12" >
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
                  <textarea cols="20" class="data" name="Comment" ></textarea>
                </td>
              </tr>
          </table>
              <button type="Reset">Clear</button>
              <input name="submitButton" type="submit" id="submitButton" value="Create">

            </form>
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
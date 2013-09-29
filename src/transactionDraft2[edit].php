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
				+ validateDropdown("Status")
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
			
			// validate functions ----- end
			function disableRadio(name, bool)
			{
				var radioButts = document.getElementsByName(name);
        var size = radioButts.length;
        for(var i = 0; i< size; i++)
        {
          if(bool)
            radioButts[i].setAttribute("disabled", "disabled");
          else
            radioButts[i].removeAttribute("disabled");
        }
			}
			// Gets all elements with the given class name
			// and set to readonly if bool = true
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
        if(!bool)
          document.getElementById("Status").removeAttribute("disabled");
        else
          document.getElementById("Status").setAttribute("disabled", "disabled");

         disableRadio("Type", bool);
			}
			


/* 			<?php    
				if(isset($_POST['SubmitButton']))
				{
				$sql="INSERT INTO Transaction (Description, Comment, TransactionDate, PaymentDate, ResponsibleParty, AssociatedParty, Amount)
VALUES
('$_POST[Description]','$_POST[Comment]','$_POST[TransactionDate]','$_POST[PaymentDate]','$_POST[ResponsibleParty]','$_POST[AssociatedParty]','$_POST[Amount]')";
									
					mysql_query($sql) or die(mysql_error());
				} 
				//TODO else	
			?>  */
      
      
                // tabs stuff ---------
    // initialise arrays
    // http://www.elated.com/articles/javascript-tabs/

    var tabLinks = new Array();
    var contentDivs = new Array();

    function initialiseTabs() {

      // Grab the tab links and content divs from the page
      var tabs = document.getElementById("tabs").childNodes;
      var length = tabs.length;
      for ( var i = 0; i < length; i++ ) 
      {
        if ( tabs[i].nodeName == "LI" ) 
        {
          var tabLink = getFirstChildWithTagName( tabs[i], 'A' );
          var id = getHash( tabLink.getAttribute('href') );
          tabLinks[id] = tabLink;
          contentDivs[id] = document.getElementById( id );
        }
      }

      // Assign onclick events to the tab links, and
      // highlight the first tab
      var i = 0;

      for ( var id in tabLinks ) 
      {
        tabLinks[id].onclick = showTab;
        tabLinks[id].onfocus = function() { this.blur() };
        if ( i == 0 )
        {
          tabLinks[id].className = 'selected';
        }
        i++;
      }

      // Hide all content divs except the first
      var i = 0;

      for ( var id in contentDivs ) 
      {
        if ( i != 0 ) contentDivs[id].className = 'tabContent hide';
        i++;
      }
    }

    function showTab() 
    {
      var selectedId = getHash( this.getAttribute('href') );

      // Highlight the selected tab, and dim all others.
      // Also show the selected content div, and hide all others.
      for ( var id in contentDivs ) 
      {
        if ( id == selectedId ) 
        {
          tabLinks[id].className = 'selected';
          contentDivs[id].className = 'tabContent';
        } 
        else 
        {
          tabLinks[id].className = '';
          contentDivs[id].className = 'tabContent hide';
        }
      }

        // Stop the browser following the link
        return false;
      }

      function getFirstChildWithTagName( element, tagName ) 
      {
        var length = element.childNodes.length;
        for ( var i = 0; i < length; i++ ) 
        {
          if ( element.childNodes[i].nodeName == tagName )    return element.childNodes[i];
        }
      }

      function getHash( url ) 
      {
        var index = url.lastIndexOf ( '#' );
        return url.substring( index + 1 );
      }
		</script>	
		
		<?php    
    
      // remove single and double quotes so no errors are thrown with the sql
      function removeQuotes($string)
      {
        $string = str_replace("'","\'", $string);
        return str_replace("\"", "\\\"", $string);
      }
			if(isset($_POST['update']))
			{
      
          $sql = "INSERT INTO History".
          "(".
            "Description,". 
            "Comment,".
            "RecordedDate,".
            "TransactionDate,".
            "PaymentDate,".
            "ResponsibleParty,".
            "AssociatedParty,".
            "Amount,".
            "Inflow,".
            "StatusID".
          ")".
          "SELECT".
            "'" . removeQuotes($_POST['Description']) . "', ".
            "'" . removeQuotes($_POST['Comment']) . "', " .
            "CURRENT_TIMESTAMP,".
            "'" . $_POST['TransactionDate'] . "', ".
            "'" . $_POST['PaymentDate'] . "', ".
            "'" . $_POST['ResponsibleParty'] . "', ".
            "'" . $_POST['AssociatedParty'] . "', ".
            "'" . $_POST['Amount'] . "', ".
            "'" .  ($_POST['Type'] == "in") . "', ".
            "'" .  $_POST['Status'] . "' ".
          "FROM History ". 
          "WHERE ID = '" . $_GET['id'] . "'" ; 
 
/* 				$sql = "UPDATE History ".
						"SET Description = '" . removeQuotes($_POST['Description']) . "', ".
						"TransactionDate = '" . $_POST['TransactionDate'] . "', ".
						"Amount = '" . $_POST['Amount'] . "', ".
						"PaymentDate = '" . $_POST['PaymentDate'] . "', ".
						"ResponsibleParty = '" . $_POST['ResponsibleParty'] . "', ".
						"AssociatedParty = '" . $_POST['AssociatedParty'] . "', ".
						"Inflow = '" .  ($_POST['Type'] == "in") . "', ".
						"Comment = '" . removeQuotes($_POST['Comment']) . "'" .
						"WHERE ID = '" . $_GET['id'] . "'" ;  */
        echo($sql);
				mysql_query($sql) or die(mysql_error());
			}

			//TODO else	
		?>  
	
	<body onload="initialiseTabs()">
		<div id="main">
		
			<div id="box">
				<h1>Transaction Details</h1>

					
				<div id="content">

            <ul id="tabs">
              <li><a href="#transInfo"> Transaction Details</a></li>
              <li><a href="#transHistory"> Transaction History</a></li>
            </ul>

            
            <div class="tabContent" id="transHistory">
              <h2>History</h2>
              <div>
                <p> history-y stuff!
              </div>
            </div>
        
        
					<?php
						// Connect to database
						$sql = "SELECT * FROM History WHERE ID='" . $_GET['id'] . "'";
						$result = mysql_query($sql, $connection) or die(mysql_error());
						$row = mysql_fetch_assoc($result);
						$statusID = $row['StatusID'];
					?>	
          
          <div class="tabContent" id="transInfo">
            <table class = "formatted">
              <!-- action="toMe.php" -->

              <form name="transactionForm" onsubmit="return validateForm(this);" action="" method="post">
              <tr>
                <td  colspan = "2" class = "transactionTitle">
                  Transaction Description
                </td>
                <td></td>
                <td>
                  <select id="Status" disabled="disabled">
                    <option value=""></option>
                    <?php
                      $sql = "SELECT * FROM Status";
                      $statusIDs = mysql_query($sql, $connection) or die(mysql_error());
                      while($statusRow = mysql_fetch_array($statusIDs))
                      {
                        if($statusRow['ID'] == $statusID)
                          echo "<option value=" . $statusRow['ID'] . ">" . $statusRow['Name'] . "</option>";
                        else
                          echo "<option value=" . $statusRow['ID'] . " selected='selected'>" . $statusRow['Name'] . "</option>";
                      }
                    ?>
                  </select>
                </td>
              </tr>
              <tr>
                <td colspan="4" class = "spaceBelow">
                  <textarea class="data" name="Description" readonly="readonly"><?=$row['Description'];?></textarea>
                </td>
                
              </tr>
              <tr>
                <td class = "transactionTitle">
                  Transaction Date*:
                </td>
                <td>
                  <input type="datetime" class="data" name="TransactionDate" size="12" value="<?=$row['TransactionDate'];?>"readonly="readonly">
                </td>
                <td class = "transactionTitle col2">
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
                  <input type="datetime" class="data" name="PaymentDate" value="<?=$row['PaymentDate'];?>"size="12" readonly="readonly">
                </td>
                <td class = "transactionTitle col2">
                  Type*:
                </td>
                <td>
                  <?php
                    $checked = ($row['Inflow'] == '1') ? "checked=\"checked\"" : "";
                    $checked2 = ($checked == "") ? "checked=\"checked\"" : "";
                  ?>
                  <input type="radio" class="data" name="Type" value="in" disabled="disabled" <?=$checked;?>>Inflow <br>
                  <input type="radio" class="data" name="Type" value="out" disabled="disabled" <?=$checked2;?>>Outflow<br>
                </td>
              </tr>
              <tr>
                <td class = "transactionTitle">
                  Responsible*:
                </td>
                <td>
                  <input type="text" class="data" name="ResponsibleParty" value="<?=$row['ResponsibleParty'];?>"size="12" readonly="readonly">
                </td>
              </tr>
              <tr>
                <td class = "transactionTitle spaceBelow">
                  Associated person:
                </td>
                <td>
                  <input type="text" class="data" name="AssociatedParty" value="<?=$row['AssociatedParty'];?>"size="12" readonly="readonly">
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
                  <textarea cols="20" class="data" name="Comment" readonly="readonly"><?=$row['Comment'];?></textarea>
                </td>
              </tr>
          </table>
              <button type="Reset">Clear</button>
              <input name="update" type="submit" id="update" value="Update">

            </form>
						<button onclick="setReadonly('data',false)">Edit</button>
						<button onclick="setReadonly('data',true)">Cancel</button>
          </div>
					
							

						
						
						
				     	<!--	 '<'?php/* 
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

		
		 */
		?> -->
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

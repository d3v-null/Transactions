<!DOCTYPE html>
<?php
$connection =mysql_connect("localhost","test","test") or die("Could not connect");
// TODO : escape '\' in comment or description field
mysql_select_db("test") or die("Unable to select database");
?>

<html>
<head>
	<title>TAB TITLE</title>
    
    <style type="text/css" media="screen">
        @import url("/css/style2.css");
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
			
			
			// validate functions ----- 
      
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
		<
    
		<?php    
    
      // remove single and double quotes so no errors are thrown with the sql
      function removeQuotes($string)
      {
        $string = str_replace("'","\'", $string);
        return str_replace("\"", "\\\"", $string);
      }
      
      
			if(isset($_POST['submitButton']))
			{
				$sql = "INSERT INTO History  (Description, TransactionDate, Amount, PaymentDate, ResponsibleParty, AssociatedParty, Inflow, StatusID, Inflow, Comment)" ."VALUES (
					'" . removeQuotes($_POST['Description']) . "', ".
					"'" . $_POST['TransactionDate'] . "', ".
					"'" . $_POST['Amount'] . "', ".
					"'" . $_POST['PaymentDate'] . "', ".
					"'" . $_POST['ResponsibleParty'] . "', ".
					"'" . $_POST['AssociatedParty'] . "', ".
					"'" . ($_POST['Type']=="in") . "', ".
					"'" . $_POST['Status'] . "', ".
					"'" . $_POST['Type'] == "in" . "', ".
					"'" . removeQuotes($_POST['Comment']) . "')";
					echo $sql;

				mysql_query($sql, $connection) or die(mysql_error());
			} 
			else
			{
				echo "EEEEH";
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
        
        
          <div class="tabContent" id="transInfo">

            <table class = "formatted tabContent" id = "transinfo">
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

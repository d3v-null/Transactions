<!DOCTYPE html>
<?php
    $page_title = 'Transaction Details';
    require_once 'includes/transaction_setup.php';
    require_once 'includes/config.php';

    $user = new User();
    if(!$user->loggedIn()){
        redirect('index.php');
    }    
?>

    <html>
    <head>
    <title><?php echo $page_title?></title>
    <link rel="stylesheet" type="text/css" href="/css/style2.css">
    <link rel="stylesheet" type="text/css" href="/css/styling.css">

    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.3/jquery.min.js" type="text/javascript"></script>
    <script src="js/jquery.tabSlideOut.v1.3.js"></script>

    <script>
    // http://www.building58.com/examples/tabSlideOut.html
    // slide out tab for transaction history
    $(function(){
    $('.slide-out-div').tabSlideOut({
       tabHandle: '.handle',
       pathToTabImage: 'images/historyTab.png',
       imageHeight: '97px',
       imageWidth: '35px', 
       tabLocation: 'right', 
       speed: 300, 
       action: 'click',
       topPos: '95px', 
       fixedPosition: false,
       onLoadSlideOut: true
    });
    });
    </script>

    <script>

    // function that helps with showing the form with the
    // given history values
    function del(){
    var rem = document.getElementById("deleteMe");
    if(rem != null)
      rem.parentNode.removeChild(rem);
    }

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

    var tabLinks = new Array();
    var contentDivs = new Array();

    function changeValue(id, val)
    {
    document.getElementById(id).value = val;
    }

    function changeFormValues(uniqueID)
    {
    /*         <?php
    $sql = "SELECT ".
      "Description,". 
      "Comment,".
      "TransactionDate,".
      "PaymentDate,".
      "ResponsibleParty,".
      "AssociatedParty,".
      "Amount,".
      "Inflow,".
      "StatusID ".
      "FROM History ".
      "WHERE ID = '" . uniqueID . "'";
      $historyResult = mysql_query($sql) or die(mysql_error());
    ?>
    */
    changeValue('Description', '100');
    changeValue('Comment', '100');
    changeValue('TransactionDate', '100');
    changeValue('PaymentDate', '100');
    changeValue('ResponsibleParty', '100');
    changeValue('AssociatedParty', '100');
    changeValue('Amount', '100');
    changeValue('Type', '100');
    changeValue('Status', '100');
    } 

    </script>	
    <script>
    function showHistory(id)
    {
    if (id=="")
    {
    document.getElementById("historyVals").innerHTML="";
    return;
    }
    if (window.XMLHttpRequest)
    {// code for IE7+, Firefox, Chrome, Opera, Safari
    xmlhttp=new XMLHttpRequest();
    }
    else
    {// code for IE6, IE5
    xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
    }
    xmlhttp.onreadystatechange=function()
    {
    if (xmlhttp.readyState==4 && xmlhttp.status==200)
    {
    // del();
    document.getElementById("historyVals").innerHTML=xmlhttp.responseText;
    }
    }
    xmlhttp.open("GET","getHistory.php?id="+str,true);
    xmlhttp.send();
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
    ?>

    <?php

      $sql = "INSERT INTO History".
      "(".
        "TransactionID,". 
        "Description,". 
        "Comment,".
        "ModificationDate,".
        "TransactionDate,".
        "PaymentDate,".
        "ResponsibleParty,".
        "AssociatedParty,".
        "Amount,".
        "Inflow,".
        "StatusID".
      ")".
      "SELECT".
        "'" . $_GET['id'] . "', ".
        "'" . removeQuotes($_POST['Description']) . "', ".
        "'" . removeQuotes($_POST['Comment']) . "', " .
        "CURRENT_TIMESTAMP,".
        "'" . $_POST['TransactionDate'] . "', ".
        "'" . $_POST['PaymentDate'] . "', ".
        "'" . $_POST['ResponsibleParty'] . "', ".
        "'" . $_POST['AssociatedParty'] . "', ".
        "'" . $_POST['Amount'] . "', ".
        "'" .  ($_POST['Type'] == "in") . "', ".
        "'" . $_POST['Status'] . "' ".
      "FROM History ". 
      "WHERE ID = '" . $_GET['id'] . "'" ; 

    echo($sql);
            mysql_query($sql) or die(mysql_error());
        }
        //TODO else	
    ?>  

    <body id='main'>
    <div class="slide-out-div">
    <h3>Transaction History:</h3>
    <div>
      <?php
          // Connect to database
       /*        $parentID = mysql_query("SELECT TransactionID FROM History WHERE ID='" . $_GET['id'] . "'"); */
        $sql = "SELECT ID FROM History WHERE TransactionID='" . $_GET['id'] . "'";
        $idResult = mysql_query($sql) or die(mysql_error());
        while($idResultRows = mysql_fetch_assoc($idResult))
        {
         // echo $idResultRows['ID'];
          $sql = "SELECT ModificationDate FROM HISTORY ".
                "WHERE ".
                "ID = '". $idResultRows['ID'] ."'".
                "ORDER BY ModificationDate ASC";
          $result = mysql_query($sql) or die(mysql_error());
        
          while($row = mysql_fetch_assoc($result))
          {
            echo "<ul>";
            echo"<li><div class='history' id='" . $idResultRows['ID'] . "' onclick='showHistory(this.id)'>" . $row['ModificationDate'] . "</div></li>";
            echo"</ul>";
            //echo $row['ModificationDate'] ."______".  $row['ID'];
            print "<br>";
          }
        }
      ?>
    </div>
    </div>

        <div id="box">
            <?php include 'subheader.php' ?>
            <div id="content">
                <?php
                    // Connect to database
                    $sql = "SELECT * FROM History WHERE ID='" . $_GET['id'] . "'";
                    $result = mysql_query($sql ) or die(mysql_error());
                    $row = mysql_fetch_assoc($result);
                    $statusID = intval($row['StatusID']);
                ?>	

      
        <div id="historyVals">
          <div id="deleteMe"> 
            <table class = "formatted">
              <form name="transactionForm" onsubmit="return validateForm(this);" action="" method="post">
                <tr>
                  <td  colspan = "2" class = "transactionTitle">
                    Transaction Description
                  </td>
                  <td></td>
                  <td>              
                    <select id="Status" name="Status" disabled="disabled">
                      <option value=""></option>
                      <?php
                        $sql = "SELECT * FROM Status";
                        $statusIDs = mysql_query($sql ) or die(mysql_error());
                        while($statusRow = mysql_fetch_array($statusIDs))
                        {
                          if(intval($statusRow['ID']) == $statusID)
                            echo "<option value=" . $statusRow['ID'] . " selected='selected'>" . $statusRow['Name'] . "</option>";
                          else
                            echo "<option value=" . $statusRow['ID'] . " >" . $statusRow['Name'] . "</option>";
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
                    <input type="text" class="data" name="Amount" id="Amount" size="8"  value="<?=$row['Amount'];?>" readonly="readonly">
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
              <input name="update" type="submit" id="update" value="Update">
              </form>
            <button onclick="setReadonly('data',false)">Edit</button>
            <button onclick="setReadonly('data',true)">Cancel</button>
          </div> <!--delete end-->
        </div><!--historyValues end-->
    </div><!-- end content!-->
    </div><!-- end box -->
    <div id="sidebar">
    <?php include_once("sidebar.php")?>
    </div>
</body id='main'>
</html>

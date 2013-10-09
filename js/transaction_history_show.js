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

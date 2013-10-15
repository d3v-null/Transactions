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
    error  +=
        validateDropdown("Status") +
        validateDropdown("Inflow")

    if(error != "")
    {
        alert("Some fields need correction: \n" + error);
        return false;
    }
    return true;
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

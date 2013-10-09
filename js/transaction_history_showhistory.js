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
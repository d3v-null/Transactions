// Expand function
function showID(elem){
    if(document.getElementById(elem).style.display=='none')
        document.getElementById(elem).style.display = 'block';
}

// Contract function
function hideID(elem){
    if(document.getElementById(elem).style.display!='none')
        document.getElementById(elem).style.display = 'block';
}
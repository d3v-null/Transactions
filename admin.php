<!DOCTYPE html>

<?php
// Create connection
$connection =mysql_connect("localhost","test","test") or die("Could not connect");

mysql_select_db("test") or die("Unable to select database");

echo "Connected to database! <br>";
?>

<html>
<head>
<!--Insert header here--> 
</head>

<body>
<div id="sidebar"> SIDEBAR </div>
<!-- Load sidebar content -->

<div id="main"> MAIN CONTENT </div>

</body>
</html>

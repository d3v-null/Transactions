<?php 
    echo serialize($_POST);
?>
<form action='test.php' method='post'>
    <input type="text" name="text">
    <input type="submit" name="Save" value="Save">
    <input type="submit" name="Delete" value="Delete">
</form>
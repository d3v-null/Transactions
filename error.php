<?php

echo "<h1>Error</h1>";
echo "<p>Sorry, your request could not be processed</p>";
if(isset($_GET['msg'])){
    echo "<p>".$_GET['msg']."</p>";
}
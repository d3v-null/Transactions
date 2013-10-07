<?php
    require_once 'includes/constants.php';
    require_once 'includes/config.php';

    $user = new User();
    if(!$user->loggedIn()){
        redirect('index.php');
    }
    
    // Connect to transaction database
    mysql_connect(DB_SERVER, DB_USER, DB_PASSWORD) or die(mysql_error());
    mysql_select_db(DB_NAME) or die(mysql_error());
    
    if(!key_exists('id', $_GET)){
        echo "<script>alert('Category ID Not specified')</script>";
    } else if (!$user->isTreasurer()){
        echo "<script>alert('You must have treasurer privileges to delete a category')</script>";
    } else {
        $sql="DELETE FROM category WHERE category.ID ='". $_GET['id']."'";
        mysql_query($sql) or die("cannot delete category: ".mysql_error());
    }
    
    if($_SERVER['HTTP_REFERER']){
        redirect($_SERVER['HTTP_REFERER']);
    } else {
        redirect("search.php");
    }
?>
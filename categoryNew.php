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
    
	if (!$user->isTreasurer()){
        echo "<script>alert('You must have treasurer privileges to create a new category. You are going to be redirected to the main page')</script>";
		echo "<meta http-equiv='Refresh' content='0; URL=search.php'>";
    } else {
		$sql="INSERT INTO Category (Name, Description) VALUES ('New Category','')";
		mysql_query($sql) or die("Category cannot be created: ".mysql_error());
		redirect("category.php?id=".mysql_insert_id());
	}
?>    

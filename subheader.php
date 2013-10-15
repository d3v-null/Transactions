<h1><?php echo $page_title?></h1>
<?php
    echo "<a href='index.php?logout=1' class='btn btn-default'>Logout</a>";
    require_once 'includes/config.php';
    if ($user->isAdmin()) {
        echo "<a href='admin.php' class='btn btn-info'>Admin</a>";
        echo "<a href='transaction.php?new' class='btn btn-info'>New Transaction</a>";
    }
    echo "logged in as "
    //do 
?>       
<html>
	<head>
		<title>Create New Sub Category</title>

		<style type="text/css" media="screen">
			@import url("css/style2.css");
			@import url("css/styling.css");
		</style>
             <div id="fakeHR"></div>
    </head>
</html>
     
<h1><?php echo $page_title?></h1>
<a href="index.php?logout=1" class="btn btn-default">Logout</a>
<?php
    echo "<a href='index.php?logout=1' class="btn btn-default'>Logout</a>'
    require_once 'includes/config.php';
    if ($user->isAdmin()) {
        echo "<a href='admin.php' class='btn btn-info'>Admin</a>";
    }
    echo "logged in as "
    //do lookup
?>            
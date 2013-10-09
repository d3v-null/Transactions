<h1><?php echo $page_title?></h1>
<a href="index.php?logout=1" class="btn btn-default">Logout</a>
<?php
    if ($user->isAdmin()) {
        echo "<a href='admin.php' class='btn btn-info'>Admin</a>";
    }
?>            
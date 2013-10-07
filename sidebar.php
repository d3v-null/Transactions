<!DOCTYPE html>
<?php  
    require_once 'includes/constants.php';
?>
<html>
    <?php
        // Connect to transaction database

		mysql_connect(DB_SERVER, DB_USER, DB_PASSWORD) or die(mysql_error());
		mysql_select_db(DB_NAME) or die(mysql_error());
    ?>

    <head>
        <link rel='stylesheet' type='text/css' href='/css/stylessiderbar.css' />
        <script src='http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js'></script>
        <script type='text/javascript' src='/js/menu_jquery.js'></script>
    </head>
    <body>
    <ul id='cssmenu'>
        <li class='active'><a href='search.php'><span>Home</span></a>
        <?php
            // Select everything from Category
            $catTable = mysql_query("SELECT * FROM Category");
            // For each row of Category
            while ($row = mysql_fetch_array($catTable)) {
                // Save Category ID
                $catID = $row['ID'];
                
                echo "<li class='has-sub'>
                    <a href='categoryEdit.php?id=". $row['ID'] . "'>
                    <img align='right' src='images/pencil.png'/>
                    </a><span>" . $row['Name'] . "</span></li>";
                echo "<ul>";
                $subCatTable = mysql_query("SELECT * FROM SubCategory WHERE SubCategory.CategoryID = $catID ORDER BY Name ASC");
                while ($subRow = mysql_fetch_array($subCatTable)) {
                    echo "<li><a href='subcategory.php?id=" . $subRow['ID'] . "'>" . $subRow['Name'] . "</a></li>";
                }
                echo "<li><a href='subcategoryNew.php?ID=" . $catID. "'><span>Add New Subcategory</span></a></li>";
                echo "</ul>";
            }
        ?>
                    
        <li class='active'><a href='categoryNew.php'><span>Create New Category</span></a>
    </ul>
    </body>
</html>

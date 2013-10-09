<!DOCTYPE html>
<?php  
    require_once 'includes/transaction_setup.php';
?>
<html>
    <head>
        <link rel='stylesheet' type='text/css' href='/css/stylessiderbar.css' />
        <script src='http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js'></script>
        <script type='text/javascript' src='/js/menu_jquery.js'></script>
    </head>
    <body>
    <div id='cssmenu'>
    <ul>
        <li class='active'><a href='search.php'><span>Home</span></a>
        <?php
            // Select everything from Category
            $cats = mysql_query("SELECT * FROM Category");
            // For each row of Category
            while ($row = mysql_fetch_array($cats)) {
                // Save Category ID
                $catID = $row['ID'];
                
                echo "<li class='has-sub'><a href='category.php?id=".$row['ID']."'><span>".$row['Name'];
                echo "</span></a><ul>";
                echo "<li><a href='category.php?id=".$row['ID']."'>
                      <span>edit <img src='images/pencil.png'/></span></a></li>";                
                $subCats = mysql_query("SELECT * FROM SubCategory WHERE SubCategory.CategoryID=".$catID." 
                                        ORDER BY Name ASC");
                while ($subRow = mysql_fetch_array($subCats)) {
                    echo "<li><a href='subcategory.php?id=".$subRow['ID']."'><span>".$subRow['Name']."</span></a></li>";
                }
                echo "<li><a href='subcategoryNew.php?ID=".$catID."'>
                      <span>Add New Subcategory</span></a></li>";
                echo "</ul>";
                echo "</li>";
            }
        ?>
                    
        <li class='active'><a href='categoryNew.php'><span>Create New Category</span></a></li>
    </ul>
    </div>
    </body>
</html>

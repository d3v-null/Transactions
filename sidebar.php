<!DOCTYPE html>
<?php  
    require_once 'includes/transaction_setup.php';
    $protocol = strpos(strtolower($_SERVER['SERVER_PROTOCOL']),'https') === FALSE ? 'http' : 'https';
    $host     = $_SERVER['HTTP_HOST'];
    $script   = $_SERVER['SCRIPT_NAME'];
    $params   = $_SERVER['QUERY_STRING'];
    $urlsub = $script . '?' . $params;
    $urlsearch = $script ;
?>
<html>
  <head>
    <title>Sidebar</title>
    <meta charset="utf-8"/>
    <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap.min.css">
    <!--[if lt IE 9]>
    <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->

    <link href="js/jquery.mCustomScrollbar.css" rel="stylesheet" />
    <!-- http://manos.malihu.gr/jquery-custom-content-scroller/ -->
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
    <script>!window.jQuery && document.write(unescape('%3Cscript src="js/jquery-1.9.1.min.js"%3E%3C/script%3E'))</script>
    <script src="js/innerScroll.js"></script>
    <script src="js/jquery.mCustomScrollbar.concat.min.js"></script>
  
  </head>
  <body>
    <form name="myform" action="http://www.mydomain.com/myformhandler.cgi" method="POST">
    <div class="panel-group" id="accordion">
      <div class="panel panel-default">
        <div class="panel-heading">
          <h4 class="panel-title">
            <a href="search.php">
              Home 
            </a>
          </h4>
        </div>
      </div>

        <?php
        // Select everything from Category
            $sql = mysql_query("SELECT * FROM Category");
            // For each row of Category
            while ($row = mysql_fetch_array($sql)) {
                // Save Category ID
                $catID = $row['ID'];

				echo "<div class='panel panel-default'>";
				echo "<div class='panel-heading'>";
				echo "<h4 class='panel-title'>";
				echo "<a class='accordion-toggle' data-toggle='collapse' data-parent='#accordion' href='#expanded".$row['ID']."''>
					<span>".$row['Name']."</span></a>";
				echo "<a href='category.php?id=".$row['ID']."'>
					<img src='images/pencil.png' border='0' align='right' />
					</a>
				  </h4>
				</div>";
				$subCats = mysql_query("SELECT * FROM SubCategory WHERE SubCategory.CategoryID=".$catID." 
										ORDER BY Name ASC");
				echo "<div id='expanded".$row['ID']."'' class='panel-collapse collapse'>
					<div class='panel-body'>";
					while ($subRow = mysql_fetch_array($subCats)) 
					{
              if ($urlsearch != '/search.php')
              {
                echo "<li><a href='subcategory.php?id=".$subRow['ID']."'>
                <span>".$subRow['Name']."</span></a></li>";
              } 
              else                
              {  
                echo "<li><a href='subcategory.php?id=".$subRow['ID']."'>
                <span><input type='checkbox' name='Subcategories[]' value='".$subRow['ID']."'>".$subRow['Name']."<br></span></a></li>";
              } 
					}
					   echo "<li class='last'><a href='subcategoryNew.php?ID=".$catID."'>
					     <span>Add New Subcategory</span></a></li>";
				      echo "</div>";
            echo"</div>";
            echo "</div>";
			  
            }
      ?>

      <div class="panel panel-default">
        <div class="panel-heading">
          <h4 class="panel-title">
            <a href="category.php?new" class='accordion-toggle' data-parent='#accordion'>
              Create New Category
            </a>
          </h4>
        </div>
        </div>
      </div>    

    <script src='//code.jquery.com/jquery.js'></script>
    <script src='//netdna.bootstrapcdn.com/bootstrap/3.0.0/js/bootstrap.min.js'></script>
  </body>
</html>
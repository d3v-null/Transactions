<!DOCTYPE html>
<?php  

require_once 'includes/transaction_setup.php';
$showBoxes = (isset($showBoxes))?$showBoxes:false;
$showRadios = (isset($showRadios))?$showRadios:false;
$checked   = (isset($checked))?$checked:[];

?>

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
<script type="text/javascript">
    MYLIBRARY.init("#sidebar");
    MYLIBRARY.scroll();
</script>

<script src="js/jquery.mCustomScrollbar.concat.min.js"></script>
<style>
  input.checkbox{
    float:right;
  }
</style>
<div class="panel-group" id="accordion">
  <div class="panel panel-default">
    <div class="panel-heading">
      <h4 class="panel-title">
          <a href="search.php" id="home">
            <img src="images/homeIcon2.png"alt="Home" height="27" width="23"  style="margin-right:5px";/>
            Home 
          </a>
        </h4>
      </div><!-- end panel panel-default-->
    </div><!-- end panel group-->

    <?php
    // Select everything from Category
    $sql = mysql_query("SELECT * FROM Category");
    // For each row of Category
    while ($row = mysql_fetch_array($sql)) {
        // Save Category ID
        $catID = $row['ID'];


        echo 
          "<div class='panel panel-default'>".
            "<div class='panel-heading'>".
              "<h4 class='panel-title'>".
                "<a class='accordion-toggle' data-toggle='collapse' data-parent='#accordion' href='#expanded".$row['ID']."'>".
                  "<span>".$row['Name']."</span>".
                "</a>".
                "<a href='category.php?id=".$row['ID']."'>".
                  "<img src='images/pencil.png' border='0' align='right' />".
                "</a>".
              "</h4>".
            "</div><!-- end panel panel-heading-->".    
          "</div><!-- end panel-default-->";  
        
        $subCats = mysql_query("SELECT * FROM SubCategory WHERE SubCategory.CategoryID=".$catID." 
                                ORDER BY Name ASC");
        echo 
          "<div id='expanded".$row['ID']."'' class='panel-collapse collapse'>".
            "<div class='panel-body'>";
        
        if($showRadios)                       
        {  
          echo 
              "<li>".
                "None".
                "<input type='radio' name='rb".$row['ID']."' value='0' class='checkbox' checked/>".
              "</li>"; 
        } 
        while ($subRow = mysql_fetch_array($subCats)) 
        {
            echo "<li>".
                    "<a href='subcategory.php?id=".$subRow['ID']."'>".
                      "<span>".$subRow['Name']."  </span>".
                    "</a>";
            if($showBoxes){
                echo 
                    "<input type='checkbox' name='sc[]' class='checkbox' ".
                    ((in_array($subRow['ID'], $checked))?"checked ":"").
                    "value='".$subRow['ID']."'/>";
            }

            if($showRadios){
                echo 
                    "<input type='radio' name='rb".$row['ID']."' class='checkbox' ".
                    ((in_array($subRow['ID'], $checked))?"checked ":"").
                    "value='".$subRow['ID']."'/>";
            }
            echo "</li>";
        }      
        echo 
            "<li class='last'>".
              "<a href='subcategoryNew.php?ID=".$catID."'>".
                "Add New Subcategory".
              "</a>".
            "</li>";
          
        echo 
            "</div><!-- end panel-body -->".
          "</div><!-- end expanded -->";
    }
  ?>
  <div class="panel panel-default">
    <div class="panel-heading">
      <h4 class="panel-title">
        <a href="category.php?new" class='accordion-toggle' data-parent='#accordion'>
          Create New Category
        </a>
      </h4>
    </div><!--end panel-heading-->
  </div><!--end panel panel-default-->
</div><!--end panel-group-->  
<script src='//code.jquery.com/jquery.js'></script>
<script src='//netdna.bootstrapcdn.com/bootstrap/3.0.0/js/bootstrap.min.js'></script>

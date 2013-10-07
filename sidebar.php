<!DOCTYPE html>
<html>
<head>
	<link rel='stylesheet' type='text/css' href='/css/stylessiderbar.css' />
	<script src='http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js'></script>
	<script type='text/javascript' src='/js/menu_jquery.js'></script>
	<?php
			$debug = True;
			// Connect to transaction database
			$dbhost = "localhost";
			$dbname = "transaction";
			$dbuser = "root";
			mysql_connect($dbhost, $dbuser) or die(mysql_error());
			mysql_select_db($dbname) or die(mysql_error());
		?>
</head>
<body>
<div id='cssmenu'>
<ul>
	<li class='active'><a href='category.php'><span>Create New Category</span></a>
	<?php
				// Select everything from Category
				$catTable = mysql_query("SELECT * FROM Category");
				// For each row of Category
				while ($row = mysql_fetch_array($catTable)) {
					// Save Category ID
					$catID = $row['ID'];
					?>
   <li class='has-sub'><a href="categoryEdit.php?id=<?php echo $row['ID']; ?>"><img align="right" src="images/pencil.png" alt="edit"/><span><?php echo $row['Name']; ?> </span></a> <?php /* link button pencil href="categoryEdit.php?id=<?php echo $row['ID']; ?>"  */ ?>
      <ul>
	  <?php
							// Select everything from Category where SubCategory.CategoryID is equals to previous CategoryID
							$subCatTable = mysql_query("SELECT * FROM SubCategory WHERE SubCategory.CategoryID = $catID ORDER BY Name ASC");
							while ($subRow = mysql_fetch_array($subCatTable)) {
								?>
         <li><a href="subcategoryEdit.php?id=<?php echo $row['ID']; ?>&subCatID=<?php echo $subRow['ID'];?>"><span><?php echo $subRow['Name'];?></span></a></li>
		 <?php
							}
						?>
		<li><a href="subcategory.php?CategoryID=<?php echo $catID; ?>"><span>Add New Subcategory</span></a></li>

      </ul>
   </li>

   <?php } //end while
			?>
</ul>
</div>

</body>
</html>

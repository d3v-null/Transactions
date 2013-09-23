<!DOCTYPE html>
	
<html>
	<head>
		<title>TAB TITLE</title>
		
		<style type="text/css" media="screen">
			@import url("style2.css");
		</style>

		<script>
					<!-- script here -->
		</script>
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
		<dl>
			<?php
				// Select everything from Category
				$catTable = mysql_query("SELECT * FROM Category");
				// For each row of Category
				while ($row = mysql_fetch_array($catTable)) {
					// Save Category ID
					$catID = $row['ID'];
					?>
					<dt>
						<!-- print Category name -->
						<?php echo $row['Name']; ?>
					</dt>
					<li>
						<?php
							// Select everything from Category where SubCategory.CategoryID is equals to previous CategoryID
							$subCatTable = mysql_query("SELECT * FROM SubCategory WHERE SubCategory.CategoryID = $catID");
							while ($subRow = mysql_fetch_array($subCatTable)) {
								?>
								<!-- print SubCategory name -->
								<dt><?php echo $subRow['Name']; ?></dt>
								<?php
							}// end while
						?>
					</li>
				<?php } //end while
			?>
		</dl>    
	</body>
</html>
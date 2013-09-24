<!DOCTYPE html>
	
<html>
<head>	
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Categories</title>
<script src="http://code.jquery.com/jquery-latest.min.js"></script>
<script src="menu_source/menu_jquery.js"></script>
<style type="text/css">
  body{padding:20px; font-size:14px; color:#000000; font-family:Arial, Helvetica, sans-serif;}
  h2 {font-weight:bold; color:#000099; margin: 20px 0px 10px 0; font-size: 1.5em;}
  p span {color:#006600; font-weight:bold; }
  a, a:link, a:visited {color:#0000FF;}
  textarea {width: 100%; padding: 10px; margin: 10px 0 15px 0; font-size: 13px; font-family: Consolas,monospace;}
  textarea.html {height: 300px;}
  textarea.css {height: 50px;}
  p {margin: 0 0 10px 0; line-height: 1.5; font-size: 1em;}
  code, pre {font-family: Consolas,monospace; color: green;}
  ol li {margin: 0 0 15px 0;}

  .thirds {width: 30%; margin: 0 5% 0 0; float: left;}
  .thirds.last {margin-right: 0; float: left;}   
  .thirds textarea {height: 50px; font-size: .9em;}
</style>
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
<style type="text/css">@import url(http://fonts.googleapis.com/css?family=Open+Sans:400,600,300);
@charset 'UTF-8';
/* Base Styles */
#cssmenu,
#cssmenu ul,
#cssmenu li,
#cssmenu a {
  margin: 0;
  padding: 0;
  border: 0;
  list-style: none;
  font-weight: normal;
  text-decoration: none;
  line-height: 1;
  font-family: 'Open Sans', sans-serif;
  font-size: 14px;
  position: relative;
}
#cssmenu a {
  line-height: 1.3;
}
#cssmenu {
  width: 250px;
  background: #fff;
  -webkit-border-radius: 4px;
  -moz-border-radius: 4px;
  border-radius: 4px;
  padding: 3px;
  -moz-box-shadow: 0 0 5px rgba(0, 0, 0, 0.6);
  -webkit-box-shadow: 0 0 5px rgba(0, 0, 0, 0.6);
  box-shadow: 0 0 5px rgba(0, 0, 0, 0.6);
}
#cssmenu > ul > li {
  margin: 0 0 2px 0;
}
#cssmenu > ul > li:last-child {
  margin: 0;
}
#cssmenu > ul > li > a {
  font-size: 15px;
  display: block;
  color: #ffffff;
  text-shadow: 0 1px 1px #000;
  background: #565656;
  background: -moz-linear-gradient(#565656 0%, #323232 100%);
  background: -webkit-gradient(linear, left top, left bottom, color-stop(0%, #565656), color-stop(100%, #323232));
  background: -webkit-linear-gradient(#565656 0%, #323232 100%);
  background: linear-gradient(#565656 0%, #323232 100%);
  border: 1px solid #000;
  -webkit-border-radius: 4px;
  -moz-border-radius: 4px;
  border-radius: 4px;
}
#cssmenu > ul > li > a > span {
  display: block;
  border: 1px solid #666666;
  padding: 6px 10px;
  -webkit-border-radius: 4px;
  -moz-border-radius: 4px;
  border-radius: 4px;
  font-weight: bold;
}
#cssmenu > ul > li > a:hover {
  text-decoration: none;
}
#cssmenu > ul > li.active {
  border-bottom: none;
}
#cssmenu > ul > li.active > a {
  background: #97be10;
  background: -moz-linear-gradient(#97be10 0%, #79980d 100%);
  background: -webkit-gradient(linear, left top, left bottom, color-stop(0%, #97be10), color-stop(100%, #79980d));
  background: -webkit-linear-gradient(#97be10 0%, #79980d 100%);
  background: linear-gradient(#97be10 0%, #79980d 100%);
  color: #fff;
  text-shadow: 0 1px 1px #000;
  border: 1px solid #79980d;
}
#cssmenu > ul > li.active > a span {
  border: 1px solid #97be10;
}
#cssmenu > ul > li.has-sub > a span {
  background: url(menu_assets/images/icon_plus.png) 98% center no-repeat;
}
#cssmenu > ul > li.has-sub.active > a span {
  background: url(menu_assets/images/icon_minus.png) 98% center no-repeat;
}
/* Sub menu */
#cssmenu ul ul {
  padding: 5px 12px;
  display: none;
}
#cssmenu ul ul li {
  padding: 3px 0;
}
#cssmenu ul ul a {
  display: block;
  color: #595959;
  font-size: 13px;
  font-weight: bold;
}
#cssmenu ul ul a:hover {
  color: #79980d;
}
</style>

<div id=cs'cssmenu'>
		<ul>
			<?php
				// Select everything from Category
				$catTable = mysql_query("SELECT * FROM Category");
				// For each row of Category
				while ($row = mysql_fetch_array($catTable)) {
					// Save Category ID
					$catID = $row['ID'];
					?>
					<li class='has-sub'><a href='#'><span><?php echo $row['Name']; ?></span></a>
						<ul>
						<?php
							// Select everything from Category where SubCategory.CategoryID is equals to previous CategoryID
							$subCatTable = mysql_query("SELECT * FROM SubCategory WHERE SubCategory.CategoryID = $catID");
							while ($subRow = mysql_fetch_array($subCatTable)) {
								?>
								<li><a href='#'><span><?php echo $subRow['Name']; ?></span></a></li>
								<?php
							}// end while
						?>
						</ul>
					</li>
				<?php } //end while
			?>
		</ul>    
		</div>

		<div style="clear:both; margin: 0 0 30px 0;">&nbsp;</div>
	</body>
</html>
 <!DOCTYPE html>
<?php
	// Create connection
	$connection =mysql_connect("localhost","test","test") or die("Could not connect");

	mysql_select_db("test") or die("Unable to select database");

	//echo "Connected to database! <br><br>";

	$sql="CREATE TABLE Tester
	(
	PID INT NOT NULL AUTO_INCREMENT,
	TransactionName CHAR(15),
	Description CHAR(225),
	Amount INT,
	PRIMARY KEY (PID, TransactionName)
	)";

	//$sql="DROP TABLE Tester";
	//mysql_query($sql) or die(mysql_error());
?>

<hmtl>

	<head>
		<!-- link styl sheet -->
		<link rel="stylesheet" type="text/css" href="styling.css">

		<script>

			// Expand function
			function expand(showMe)
			{
				if(document.getElementById(showMe).style.display=='none')
					display = 'block';
				else
					display = 'none';

				document.getElementById(showMe).style.display = display;
			}

		</script>

	</head>

	<body onload="expand('moreSearch')">


		<table id='search-box'> 
			<tr>
				<th>Advanced Search</th>
			</tr>
			<tr>
				<td>Searching stuff </td>
			</tr>
			<tr>
				<td>
					<a href="" onclick="expand('moreSearch');return false;"style = "float:right;">More</a>
				</td>
			</tr>
			<tr>
				<td>
					<div id ='moreSearch'>
						More searching stuff <br>
						BTW that search button hasnt been implemented yet
					</div>
				</td>
			</tr>
			<tr>
				<td style="float:right"><button>Search</button></td>
			</tr>
		</table>
		<br><br>


		<?php
			$sql="SELECT * FROM tester";
			$result = mysql_query($sql) or die(mysql_error());
		?>

		<table id = 'transaction-list' summary = 'List of Transactions'>
			<thead>
				<tr>
					<th scope = 'col'>
						PID
					</th>
					<th scope = 'col'>
						Transaction Name
					</th>
					<th scope = 'col'>
						Amount
					</th>
				</tr>
			</thead>
			<tbody>
				<?php
					while($row = mysql_fetch_array($result))
					{
				?>
				<tr>
					<td>
						<?=$row['PID'];?>
					</td>
					<td>
						<a href="transaction.php?id= <?=$row['PID'];?>" onclick="expand('transactD');return false;"> <?=$row['TransactionName'];?> </a>
					</td>
					<td>
						<?=$row['Amount'];?>
					<td>
				</tr>
				<?php
				}
				?>
			</tbody>
		</table>
	 
	 </body>
</html>

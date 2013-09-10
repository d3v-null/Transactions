 <!DOCTYPE html>

<?php
// Create connection
$connection =mysql_connect("localhost","test","test") or die("Could not connect");

mysql_select_db("test") or die("Unable to select database");

echo "Connected to database! <br>";
?>

<html>
	<head>
	</head>
	
	<body>
		<?php
			$sql = "SELECT * FROM Tester WHERE PID='" . $_GET['id'] . "'";
			$result = mysql_query($sql) or die(mysql_error());

			echo "<table border='1'>";

			while($row = mysql_fetch_assoc($result))
			{
				echo "<tr>";
				foreach($row as $cvalue)
				{
					echo "<td>" ;
					print "$cvalue\t";
					echo "</td>";
				}		
				echo"</tr>";
				}
			echo "</table>"; 
		?>	
		
	</body>
</html>

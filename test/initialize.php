<!-- Sets up the transaction database with all the correct tables. 
	 Requires access to transaction database -->

<?php 
	// Connect to transaction database
	$dbhost = "localhost";
	$dbname = "transaction";
	$dbuser = "transaction";
	$dbpass = "";
	mysql_connect($dbhost,$dbuser,$dbpass) or die(mysql_error());
	mysql_select_db($dbname) or die(mysql_error());
	
    //Drop tables
    mysql_query("DROP TABLE IF EXISTS Categorization");
    mysql_query("DROP TABLE IF EXISTS SubCategory");
    mysql_query("DROP TABLE IF EXISTS MetaCategory");
    mysql_query("DROP TABLE IF EXISTS Status");
    mysql_query("DROP TABLE IF EXISTS History");
    
	//Status
	mysql_query("
		CREATE TABLE Status (
			ID INT AUTO_INCREMENT PRIMARY KEY,
			Name VARCHAR(50) NOT NULL,
			Description VARCHAR(255)
		)
	");
	mysql_query("
		INSERT INTO statuses (Name) 
		VALUES 
			('Pending'),
			('Processed'),
			('Void')
	");
	
	//Category and SubCategory
	mysql_query("
		CREATE TABLE Category (
			ID INT AUTO_INCREMENT,
            Name VARCHAR(50) NOT NULL,
			Description VARCHAR(255),
            PRIMARY KEY (ID)
		)
	");
	mysql_query("
		CREATE TABLE SubCategory (
			ID INT AUTO_INCREMENT PRIMARY KEY,
            CategoryID INT NOT NULL,
            Name VARCHAR(50) NOT NULL,
			Description VARCHAR(255),
            INDEX category_ind (CategoryID),
            FOREIGN KEY (CategoryID)
                REFERENCES Category(ID)
                ON DELETE CASCADE
                
		)
	");
        
	
	//History
	mysql_query("DROP TABLE IF EXISTS History");
	mysql_query("
		CREATE TABLE History (
			ID INT AUTO_INCREMENT PRIMARY KEY,
		)
	");
	
		
mysql_query($con,"INSERT INTO History (TransactionID, Description, Comment, Associated Party, StatusID, RecordDate, Value) 
VALUES (1, “Jimmy Neutron’s membership”, “paid in meatballs”, “Jimmy Neutron”, 2, “5/9/2013”, 10)");
mysql_query($con,"INSERT INTO History (TransactionID, Description, Comment, Associated Party, StatusID, RecordDate, Value) 
VALUES (1, “Jombles Notronbo’s family membership”, “”, “Jombles Notronbo”,1, “5/9/2013”, 40)");
mysql_query($con,"INSERT INTO History (TransactionID, Description, Comment, Associated Party, StatusID, RecordDate, Value) 
VALUES (1, “Jombles Notronbo’s family membership”, “”, “Jombles Notronbo”,2, “6/9/2013”, 40)");
mysql_query($con,"INSERT INTO Categories (CategoryID, MetaCategory, SubCategory) 
VALUES (1, “Membership”, “Single”)");
mysql_query($con,"INSERT INTO Categories (CategoryID, MetaCategory, SubCategory) 
VALUES (2, “Membership”, “Family”)");
mysql_query($con,"INSERT INTO Categories (CategoryID, MetaCategory, SubCategory) 
VALUES (3, “Event”, “Oktoberfest”)");
mysql_query($con,"INSERT INTO Categories (CategoryID, MetaCategory, SubCategory) 
VALUES (4, “Event”, “Midsommar”)");
mysql_query($con,"INSERT INTO Categories (CategoryID, MetaCategory, SubCategory) 
VALUES (5, “Expense”, “Admin”)");
mysql_query($con,"INSERT INTO Categories (CategoryID, MetaCategory, SubCategory) 
VALUES (6, “Year”, “2013”)");
mysql_query($con,"INSERT INTO Categorization (CategorizationID, TransactionID, CategoryID) 
VALUES (1, 1, 1)");
mysql_query($con,"INSERT INTO Categorization (CategorizationID, TransactionID, CategoryID) 
VALUES (2, 2, 2)");
?>
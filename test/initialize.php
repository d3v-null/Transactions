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
    mysql_query("
        DROP TABLE IF EXISTS Categorization;
        DROP TABLE IF EXISTS SubCategory;
        DROP TABLE IF EXISTS MetaCategory;
        DROP TABLE IF EXISTS Status;
        DROP TABLE IF EXISTS History
    ");
    
	//Status
	mysql_query("
		CREATE TABLE Status (
			ID INT AUTO_INCREMENT PRIMARY KEY,
			Name VARCHAR(50) UNIQUE NOT NULL,
			Description VARCHAR(255)
		);
        
		INSERT INTO statuses (Name) VALUES 
			('Pending'),
			('Processed'),
			('Void');
	");
    
    //History
	mysql_query("
		CREATE TABLE History (
            TransactionID INT NOT NULL AUTO_INCREMENT,
            Description VARCHAR(255) NOT NULL,
            Comment VARCHAR(65535),
            RecordedDate DATETIME DEFAULT(GETDATETIME()),
            TransactionDate DATETIME,
            PaymentDate DATETIME,
            RecordedPersonID INT NOT NULL,
            ResponsibleParty VARCHAR(255),
            AssociatedParty VARCHAR (255),
            StatusID INT NOT NULL,
            Amount INT NOT NULL
            
            PRIMARY KEY HistoryID (TransactionID, RecordedDate),
            
            FOREIGN KEY (StatusID) 
                REFERENCES Status(ID)
                ON DELETE CASCADE
		);
        
        INSERT INTO History (TransactionID, Description, AssociatedParty, Amount, StatusID) 
        VALUES
            (
                SELECT 1, 'Jimmy Neutron’s membership', 'Jimmy Neutron', 1000, StatusID
                FROM Status WHERE Name = 'Pending'
            ),
            (
                SELECT 2, 'Jombles Notronbo’s family membership', 'Jombles Notronbo', 4000, StatusID 
                FROM Status WHERE Name = 'Pending'
            ),
            (
                SELECT 2, 'Jombles Notronbo’s family membership', 'Jombles Notronbo', 4000, StatusID 
                FROM Status WHERE Name = 'Processed'
            );   
	");
	
	//Category and SubCategory
	mysql_query("
		CREATE TABLE Category (
			ID INT AUTO_INCREMENT PRIMARY KEY,
            Name VARCHAR(50) UNIQUE NOT NULL,
			Description VARCHAR(255)
		);
        
		CREATE TABLE SubCategory (
			ID INT AUTO_INCREMENT PRIMARY KEY,
            CategoryID INT NOT NULL,
            Name VARCHAR(50),
			Description VARCHAR(255),
            
            FOREIGN KEY (CategoryID)
                REFERENCES Category(ID)
                ON DELETE CASCADE
		);
        
        INSERT INTO Category (Name) VALUES
            ('Membership'),
            ('Event'),
            ('Expense'),
            ('Year');
            
        INSERT INTO Subcategory (Name, CategoryID) VALUES
            SELECT 'Single', ID FROM Category WHERE Name = 'Membership',
            SELECT 'Family', ID FROM Category WHERE Name = 'Membership',
            SELECT 'Oktoberfest', ID FROM Category WHERE Name = 'Event',
            SELECT 'Midsommar', ID FROM Category WHERE Name = 'Event',
            SELECT 'Admin', ID FROM Category WHERE Name = 'Expense',
            SELECT '2013', ID FROM Category WHERE Name = 'Year';
    ");    

    //Categorization
    mysql_query("
        CREATE TABLE Categorization (
            
?>
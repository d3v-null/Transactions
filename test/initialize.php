<!-- Sets up the transaction database with all the correct tables. 
	 Requires access to transaction database -->

<?php 
    $debug = True;
    If ($debug) echo "<h1>Initializing Transaction database</h1>";
    
	If ($debug) echo "<h2>Connecting to database</h2>";
	$dbhost = "localhost";
	$dbname = "transaction";
	$dbuser = "root";
	mysql_connect($dbhost,$dbuser) or die(mysql_error());
	mysql_select_db($dbname) or die(mysql_error());
	
    If ($debug) echo "<h2>Dropping tables</h2>";
    mysql_query("
        DROP TABLE IF EXISTS 
            Categorization,
            SubCategory, 
            Category,
            History,
            Status;
    ") or die(mysql_error());
    
    If ($debug) echo "<h2>Creating tables</h2>";
    If ($debug) echo "<h3>Creating Status table</h3>";
	mysql_query("
		CREATE TABLE Status (
			ID INT AUTO_INCREMENT, 
			Name VARCHAR(50) UNIQUE NOT NULL,
			Description VARCHAR(255),
            PRIMARY KEY (ID)
		);
    ") or die(mysql_error());
    
    If ($debug) echo "<h3>Creating Category table</h3>";
	mysql_query("   
		CREATE TABLE Category (
			ID INT AUTO_INCREMENT,
            Name VARCHAR(50) UNIQUE NOT NULL,
			Description VARCHAR(255),
            PRIMARY KEY (ID)
		);
    ") or die(mysql_error());
    
    If ($debug) echo "<h3>Creating SubCategory table</h3>";
	mysql_query(" 
		CREATE TABLE SubCategory (
			ID INT AUTO_INCREMENT,
            CategoryID INT NOT NULL,
            Name VARCHAR(50),
			Description VARCHAR(255),
            
            PRIMARY KEY (ID),
            FOREIGN KEY (CategoryID)
                REFERENCES Category(ID)
                ON DELETE CASCADE
		);
    ") or die(mysql_error());
    
    If ($debug) echo "<h3>Creating History table</h3>";
    mysql_query("     
        CREATE TABLE History (
            ID INT AUTO_INCREMENT,
            TransactionID INT NOT NULL,
            Description VARCHAR(255) NOT NULL,
            Comment TEXT,
            ModificationDate TIMESTAMP DEFAULT NOW(),
            TransactionDate TIMESTAMP,
            PaymentDate TIMESTAMP,
            ModificationPersonID INT NOT NULL,
            ResponsibleParty VARCHAR(255),
            AssociatedParty VARCHAR (255),
            StatusID INT NOT NULL,
            Amount INT NOT NULL,
            
            PRIMARY KEY (ID),
            FOREIGN KEY (StatusID) 
                REFERENCES Status(ID)
                ON DELETE CASCADE
		);        
    ") or die(mysql_error());
    
    If ($debug) echo "<h3>Creating Categorization table</h3>";
	mysql_query("         
        CREATE TABLE Categorization (
            ID INT AUTO_INCREMENT,
            HistoryID INT NOT NULL,
            SubCategoryID INT NOT NULL,
            
            PRIMARY KEY (ID),
            FOREIGN KEY (HistoryID)
                REFERENCES History(ID)
                ON DELETE CASCADE,
            FOREIGN KEY (SubCategoryID)
                REFERENCES SubCategory(ID)
                ON DELETE CASCADE
        );  
    ") or die(mysql_error());
    
    If ($debug) echo "<h3>Creating User table</h3>";
	mysql_query("     
        CREATE TABLE Users (
            ID INT AUTO_INCREMENT,
            Username VARCHAR (20),
            Password VARCHAR (20),
            
            PRIMARY KEY (ID) 
		);        
    ") or die(mysql_error());
    
    If ($debug) echo "<h2>Populating tables</h2>";
    If ($debug) echo "<h3>Populating status</h3>";
	mysql_query("
		INSERT INTO Status (Name) VALUES 
			('Pending'),
			('Processed'),
			('Void');
    ") or die(mysql_error()); 
    
    If ($debug) echo "<h3>Populating History</h3>";
    mysql_query("
        INSERT INTO History (TransactionID, Description, ModificationDate, 
            AssociatedParty, Amount, StatusID) 
        VALUES
            (1, 'Jimmy Neutron’s membership', '2013-09-22 18:48:43','Jimmy Neutron', 1000, 
                (SELECT ID FROM Status WHERE Name = 'Pending')),
            (2, 'Jombles Notronbo’s family membership', '2013-09-22 18:48:44', 'Jombles Notronbo', 4000, 
                (SELECT ID FROM Status WHERE Name = 'Pending')),
            (2, 'Jombles Notronbos\' family membership', '2013-09-22 18:48:45', 'Jombles Notronbos', 4000, 
                (SELECT ID FROM Status WHERE Name = 'Processed')); 
    ") or die(mysql_error()); 
    
    If ($debug) echo "<h3>Populating Category</h3>";
    mysql_query("
        INSERT INTO Category (Name) VALUES
            ('Membership'),
            ('Event'),
            ('Expense'),
            ('Year');
    ") or die(mysql_error()); 
    
    If ($debug) echo "<h3>Populating Subcategory</h3>";
    mysql_query("        
        INSERT INTO Subcategory (Name, CategoryID) VALUES
            ('Single', (SELECT ID FROM Category WHERE Name = 'Membership')),
            ('Family', (SELECT ID FROM Category WHERE Name = 'Membership')),
            ('Oktoberfest', (SELECT ID FROM Category WHERE Name = 'Event')),
            ('Midsommar', (SELECT ID FROM Category WHERE Name = 'Event')),
            ('Admin', (SELECT ID FROM Category WHERE Name = 'Expense')),
            ('2013', (SELECT ID FROM Category WHERE Name = 'Year'));
    ") or die(mysql_error()); 
    
    If ($debug) echo "<h3>Populating Categorization</h3>";
    mysql_query("   
        INSERT INTO Categorization (HistoryID, SubCategoryID) 
        VALUES
            (
                (
                    SELECT ID FROM History 
                    WHERE TransactionID = 1 AND ModificationDate='2013-09-22 18:48:43'
                ),
                (
                    SELECT SubCategory.ID FROM SubCategory 
                    INNER JOIN Category ON Subcategory.CategoryID = Category.ID
                    WHERE Category.Name = 'Membership' AND SubCategory.Name = 'Single'
                )
            ),
            (
                (
                    SELECT ID FROM History 
                    WHERE TransactionID = 2 AND ModificationDate='2013-09-22 18:48:44'
                ),
                (
                    SELECT SubCategory.ID FROM SubCategory 
                    INNER JOIN Category ON Subcategory.CategoryID = Category.ID
                    WHERE Category.Name = 'Membership' AND SubCategory.Name = 'Single'
                )
            );            
    ") or die(mysql_error()); 
    
    If ($debug) echo "<h3>Populating Users</h3>";
    mysql_query("        
        INSERT INTO Users (username, password) VALUES
            ('derwent', '5f4dcc3b5aa765d61d8327deb882cf99');
    ") or die(mysql_error()); 
    
     echo "<h2>Complete</h2>";
?>
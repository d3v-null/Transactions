<!-- Sets up the transaction database with all the correct tables. 
	 Requires access to transaction database -->

<?php 
    $debug = True;
    If ($debug) echo "<h1>Initializing Transaction database</h1>";
    
	If ($debug) echo "<h2>Connecting to transaction database</h2>";
	$dbhost = "localhost";
	$dbuser = "root";
    $dbpass = '';
    if(False){
        $dbuser = 'test';
        $dbpass = 'test';
    }
	mysql_connect($dbhost,$dbuser,$dbpass) or die(mysql_error());
	If ($debug) echo "<h2>Creating transaction database</h2>";
    mysql_query("
        CREATE DATABASE transaction
    ");
	mysql_select_db("transaction") or die(mysql_error());
    
    If ($debug) echo "<h2>Dropping tables</h2>";
    mysql_query("
        DROP TABLE IF EXISTS 
            Categorization,
            SubCategory, 
            Category,
            History,
            Status,
            Transaction,
            Users;
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
    
    If ($debug) echo "<h3>Creating Transaction table</h3>";
	mysql_query("   
		CREATE TABLE Transaction (
			ID INT AUTO_INCREMENT,
            PRIMARY KEY (ID)
		);
    ") or die(mysql_error());    
    
    If ($debug) echo "<h3>Creating History table</h3>";
    mysql_query("     
        CREATE TABLE History (
            ID INT AUTO_INCREMENT,
            TransactionID INT,
            Description VARCHAR(255) NOT NULL,
            Comment TEXT,
            ModificationDate TIMESTAMP DEFAULT NOW(),
            TransactionDate DATE,
            PaymentDate DATE,
            ModificationPersonID INT NOT NULL,
            ResponsibleParty VARCHAR(255),
            AssociatedParty VARCHAR (255),
            StatusID INT NOT NULL,
            Amount INT NOT NULL,
            Inflow BOOLEAN NOT NULL,
            
            PRIMARY KEY (ID),
            FOREIGN KEY (StatusID) 
                REFERENCES Status(ID)
                ON DELETE CASCADE,
            FOREIGN KEY (TransactionID)
                REFERENCES Transaction(ID)
                ON DELETE CASCADE
		);        
    ") or die(mysql_error());
    
    If ($debug) echo "<h3>Creating Categorization table</h3>";
	mysql_query("         
        CREATE TABLE Categorization (
            ID INT AUTO_INCREMENT,
            TransactionID INT NOT NULL,
            SubCategoryID INT NOT NULL,
            
            PRIMARY KEY (ID),
            FOREIGN KEY (TransactionID)
                REFERENCES Transaction(ID)
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
    
    // If ($debug) echo "<h3>Populating transaction</h3>";
	// mysql_query("
		// INSERT INTO Transaction (ID) VALUES
            // (1), 
            // (2),
            // (10),
            // (11),
            // (13);
    // ") or die(mysql_error()); 
        
    
    If ($debug) echo "<h3>Populating status</h3>";
	mysql_query("
		INSERT INTO Status (Name) VALUES 
			('Pending'),
			('Processed'),
			('Void');
    ") or die(mysql_error()); 
    
    
    // If ($debug) echo "<h3>Populating History</h3>";
    // mysql_query("
        // INSERT INTO History (TransactionID, Description, ModificationDate, 
            // AssociatedParty, Amount, StatusID, Inflow) 
        // VALUES
            // (1, 'Jimmy Neutron’s membership', '2013-09-22 18:48:43','Jimmy Neutron', 1000, 
                // (SELECT ID FROM Status WHERE Name = 'Pending'), 0),
            // (2, 'Jombles Notronbo’s family membership', '2013-09-22 18:48:44', 'Jombles Notronbo', 4000, 
                // (SELECT ID FROM Status WHERE Name = 'Pending'), 0),
            // (2, 'Jombles Notronbos\' family membership', '2013-09-22 18:48:45', 'Jombles Notronbos', 4000, 
                // (SELECT ID FROM Status WHERE Name = 'Processed'), 0); 
    // ") or die(mysql_error()); 
    
    // mysql_query("INSERT INTO `history` (`ID`, `TransactionID`, `Description`, `Comment`, `ModificationDate`, `TransactionDate`, `PaymentDate`, `ModificationPersonID`, `ResponsibleParty`, `AssociatedParty`, `Amount`, `Inflow`, `StatusID`) VALUES
// (11, 11, 'Bought 50 gold pencils', 'They looked so purdy and I couldn''t resist! :}', '0000-00-00 00:00:00', '2012-12-12 00:00:00', '2012-12-12 00:00:00', 0, 'Bob the Builder', 'Someone', 1, 0, 1),
// (12, 10, 'Sold a meatball', 'He knew it''d be tasty! \"I''ll love it\", he said.', '0000-00-00 00:00:00', '0001-01-01 00:00:00', '2012-12-12 00:00:00', 0, 'Bob the Builder', 'Wendy the Other Person', 124, 0, 1),
// (32, 11, 'id is 1, and complete', 'They looked so purdy and I couldn''t resist! :}', '2013-10-03 22:24:52', '2012-12-12 00:00:00', '2012-12-12 00:00:00', 0, 'Bob the Builder', 'Someone', 1, 0, 2),
// (34, 11, 'id is 1, and complete', 'They looked so purdy and I couldn''t resist! :}', '2013-10-03 22:26:06', '2012-12-12 00:00:00', '2012-12-12 00:00:00', 0, 'Bob the Builder', 'Someone', 1, 1, 2),
// (35, 13, 'Bought 50 gold pencils id3', 'They looked so purdy and I couldn''t resist! :}', '2013-10-03 22:26:49', '2012-12-12 00:00:00', '2012-12-12 00:00:00', 0, 'Bob the Builder', 'Someone', 1, 0, 1),
// (36, 11, 'Bought 50 gold pencils', 'They looked so purdy and I couldn''t resist! :}', '2013-10-03 22:28:37', '2012-12-12 00:00:00', '2012-12-12 00:00:00', 0, 'Bob the Builder', 'Someone', 1, 0, 2),
// (37, 11, 'Bought 50 gold pencils. Updated!!', 'They looked so purdy and I couldn''t resist! :}', '2013-10-07 10:58:48', '2012-12-12 00:00:00', '2012-12-12 00:00:00', 0, 'Bob the Builder', 'Someone', 1, 0, 1),
// (38, 11, 'Bought 50 gold pencils', 'They looked so purdy and I couldn''t resist! :}', '2013-10-07 14:12:19', '2012-12-12 00:00:00', '2012-12-12 00:00:00', 0, 'Bob the Builder', 'Someone', 19898, 0, 1);") or die(mysql_error());
    
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
    
    // If ($debug) echo "<h3>Populating Categorization</h3>";
    // mysql_query("   
        // INSERT INTO Categorization (TransactionID, SubCategoryID) 
        // VALUES
            // (
                // 1,
                // (
                    // SELECT SubCategory.ID FROM SubCategory 
                    // INNER JOIN Category ON Subcategory.CategoryID = Category.ID
                    // WHERE Category.Name = 'Membership' AND SubCategory.Name = 'Single'
                // )
            // ),
            // (
                // 2,
                // (
                    // SELECT SubCategory.ID FROM SubCategory 
                    // INNER JOIN Category ON Subcategory.CategoryID = Category.ID
                    // WHERE Category.Name = 'Membership' AND SubCategory.Name = 'Family'
                // )
            // );            
    // ") or die(mysql_error()); 
    
    // If ($debug) echo "<h3>Populating Users</h3>";
    // mysql_query("        
        // INSERT INTO Users (username, password) VALUES
            // ('derwent', '5f4dcc3b5aa765d61d8327deb882cf99');
    // ") or die(mysql_error()); 
    
     echo "<h2>Complete</h2>";
     
    If ($debug) echo "<h1>Initializing User database</h1>";
    
	If ($debug) echo "<h2>Creating user_db database</h2>";
    mysql_query("
        CREATE DATABASE user_db
    ");
	mysql_select_db("user_db") or die(mysql_error());    
    
	If ($debug) echo "<h2>Connecting to User database</h2>";
	$dbname = "user_db";
    mysql_connect($dbhost,$dbuser,$dbpass) or die(mysql_error());
	mysql_select_db($dbname) or die(mysql_error());
    
    If ($debug) echo "<h3>Creating login_attempt table</h3>";
	mysql_query(" 
        CREATE TABLE IF NOT EXISTS `login_attempt` (
        `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
        `ip` int(11) unsigned NOT NULL,
        `email` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
        `ts` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (`id`),
        KEY `ip` (`ip`)
        ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

    ") or die(mysql_error());
    
    If ($debug) echo "<h3>Creating users table</h3>";
	mysql_query(" 
        CREATE TABLE IF NOT EXISTS `users` (
        `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
        `email` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
        `rank` tinyint(2) unsigned NOT NULL,
        `registered` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
        `last_login` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
        `token` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
        `token_validity` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
        PRIMARY KEY (`id`),
        UNIQUE KEY `email` (`email`),
        UNIQUE KEY `token` (`token`)
        ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
    ") or die(mysql_error());    
?>
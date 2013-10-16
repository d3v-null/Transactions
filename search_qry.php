<?php

$src="
    SELECT
        History.ID                    AS ID,
        History.TransactionID         AS TransactionID,
        DATE(History.TransactionDate) AS TransactionDate,
        History.Description           AS Description,
        History.Amount                AS Amount,
        Status.Name                   AS Status       
    FROM 
        (
            SELECT 
                Transaction.ID,
                max(ModificationDate) AS ModificationDate
            FROM 
                (
                    SELECT DISTINCT Transaction.ID
                    FROM 
                        Categorization 
                        INNER JOIN Transaction
                    ON Categorization.TransactionID = Transaction.ID
                    WHERE Categorization.SubcategoryID IN (1,2,3)
                )
                AS Transaction
                INNER JOIN History
                ON History.TransactionID = Transaction.ID
            GROUP BY History.TransactionID
        )
        AS LATEST
        INNER JOIN History 
        ON Latest.ID = History.TransactionID 
        AND Latest.ModificationDate = History.ModificationDate
        INNER JOIN Status
        ON Status.ID = History.StatusID 
    WHERE 1
"

//SELECT * FROM Transaction JOIN Categorization ON Transaction.ID = Categorization.TransactionID


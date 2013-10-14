<?php
//does not work by itself, requires $fieldGen->vals

require_once 'includes/transaction_setup.php';
require_once 'includes/config.php';
$user = new User();
if(!$user->loggedIn()){
    redirect('index.php');
}

isset($fieldGen->vals)?
    $id = $fieldGen->vals['TransactionID']:
    die('no TransactionID available');

echo "<h2>Modification History</h2>";

$sql = "SELECT ID, ModificationDate, ModificationPersonID 
        FROM History WHERE TransactionID = ".$id." ".
        "ORDER BY ModificationDate ASC";
$result = mysql_query($sql) or die(mysql_error());
echo "<table class='history' >";
while($row = mysql_fetch_array($result)){
    echo 
        "<tr id='".$row['ID']."' onclick=\"document.location='transaction.php?id=".$row['ID']."';\">".
            "<a href='transaction.php?id=".$row['ID']."'>".
                "<td>".$row['ID']."</a></td>".
                "<td>".$row['ModificationDate']."</td>".
                "<td>".$row['ModificationPersonID']."</td>".//do a lookup instead
            "</a>".
        "</tr>";
}
echo "</table>";    
?>
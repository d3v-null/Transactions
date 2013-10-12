 <?php
$page_title = 'Modification History';
$page_table = 'history';

require_once 'includes/transaction_setup.php';
require_once 'includes/config.php';
$user = new User();
if(!$user->loggedIn()){
    redirect('index.php');
}

//die if no transaction ID specified
$id=(key_exists('id', $_GET)) ? $_GET["id"] : die("No Transaction ID specified");

echo "<h2>".$page_title."</h2>";

$sql = "SELECT ID, ModificationDate, ModificationPersonID 
        FROM History WHERE TransactionID = ".$id." ".
        "ORDER BY ModificationDate ASC";
$result = mysql_query($sql) or die(mysql_error());
echo "<ul>";
while($row = mysql_fetch_array($result)){
    echo 
        "<li class='history' id='".$row['ID'].">".
            "<a href='transaction.php?id=".$row['ID']."'>".
                $row['ID']."</br>".
                $row['ModificationDate']."</br>".
                $row['ModificationPersonID'].//do a lookup instead
            "</a>".
        "</li>";
}    
?>
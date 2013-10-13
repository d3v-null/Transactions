<?php
$page_title = 'Transaction Details';
$page_table = 'history';

include 'FieldGen.php';
require_once 'includes/transaction_setup.php';
require_once 'includes/config.php';
$user = new User();
if(!$user->loggedIn()){
    redirect('index.php');
}

//die if no history ID specified
$id=(key_exists('id', $_GET)) ? $_GET["id"] : die("No History ID specified");

//Check ID is valid
$fetch = FieldGen::fetch($page_table, $id);

//If delete button was pressed
if(!empty($_POST) && key_exists('delete', $_POST)){
    if (!$user->isTreasurer()){
        echo "<script>alert('You must have treasurer privileges to create a transaction')</script>";
    } else { 
        //Check things
        //Do delete things
        //Redirect
    }
}

$fieldGen = new FieldGen();
$fieldGen->lbls = array(
    'TransactionDate'   => 'Date of Transaction', 
    'PaymentDate'       => 'Date of Payment',
    'ResponsibleParty'  => 'Party responsible for transaction',
    'AssociatedParty'   => 'Party associated with transaction',
    'Inflow'            => 'Payment Type',
    'StatusID'          => 'Status'
);
$fieldGen->parse_metadata(DB_NAME, $page_table);
$fieldGen->add_rule(
    'Description',
    new FieldRule(
        'Description must be unique',
        function($d){
            $sql = "SELECT History.Description FROM (
                        SELECT TransactionID, Max(ModificationDate) AS ModificationDate
                        FROM History
                        GROUP BY TransactionID
                    ) AS Latest
                    INNER JOIN History ON Latest.TransactionID = History.TransactionID
                    AND Latest.ModificationDate = History.ModificationDate
                    WHERE History.Description = ".$d;
            return !mysql_query($sql) or die(mysql_error());
        }
    )
);
$fieldGen->add_rule(
    'StatusID',
    new FieldRule(
        'A valid status must be selected',  
        function($s){
            $sql = "SELECT StatusID FROM Status WHERE StatusID =".$s;
            return mysql_query($sql) or die(mysql_error());
        }
    )
);
$fieldGen->add_rule(
    'Amount',
    new FieldRule(
        'Amount must be numeric',
        "is_numeric"
    )
);
$fieldGen->add_rule(
    'Amount',
    new FieldRule(
        'Amount must be positive',
        function($a){
            return $a>=0;
        }
    )
);
$fieldGen->add_rule(
    'Inflow',
    new FieldRule(
        'A valid payment type must be entered',
        function($a){
            return in_array($a, array(1,2));
        }
    )
);

// If update button was pressed
if(!empty($_POST) && isset($_POST['update']))
{
    if (!$user->isTreasurer()){
        echo "<script>alert('You must have treasurer privileges to modify a transaction')</script>";
    } else { 
        $fieldGen->parse($_POST);
 
        $fieldGen->vals['ModificationDate'] = date('Y-m-d h:m:s');
        $fieldGen->vals['ModificationPersonID'] = 
            (isset($_SESSION['loginid']))?$_SESSION['loginid']:die("No login available");
        $fieldGen->vals['Amount'] *= 100;
        //check for errors     
        echo serialize($fieldGen->vals);
        echo "</br>";       
        
        $sql =  "INSERT INTO history (".
                    implode(", ", array_keys($fieldGen->vals)).
                ") VALUES (".
                    implode(", ", array_values($fieldGen->vals)).
                ") ";
        
        echo($sql);
        //mysql_query($sql) or die(mysql_error());
        //$id = mysql_insert_id();
    }
} else {
    $fieldGen->parse($fetch);
}

//generate status options
$rslt = mysql_query("SELECT * FROM Status") or die(mysql_error());
$sopts = array( 0 => '-- select --' );
while($row = mysql_fetch_array($rslt))
{
    $sopts[$row['ID']] = $row['Name'];
}

$iopts = array( 
    0 => '-- select --',
         'inflow',
         'outflow',
);

$fmat = array(
    'Description'       => FieldGen::inputFormat('text'),
    'StatusID'          => FieldGen::optionFormat($sopts),
    'TransactionDate'   => FieldGen::inputFormat('date'),
    'PaymentDate'       => FieldGen::inputFormat('date'),
    'ResponsibleParty'  => FieldGen::inputFormat('text'),
    'AssociatedParty'   => FieldGen::inputFormat('text'),
    'Amount'            => function ($id, $lbl, $val, $err){
        $fld = "<input name='".$id."' type='text' value='". $val/100 ."'>";
        return FieldGen::fieldList($id, $lbl, $fld, $err);
    },   
    'Inflow'            => FieldGen::optionFormat($iopts),
    'Comment'           => FieldGen::inputFormat('text'),
);    

?>  
<!DOCTYPE html>
<html>
    <head>
        <title><?php echo $page_title?></title>
        <link rel="stylesheet" type="text/css" href="/css/style2.css">
        <link rel="stylesheet" type="text/css" href="/css/styling.css">

        <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.3/jquery.min.js" type="text/javascript"></script>
        <script src="js/jquery.tabSlideOut.v1.3.js"></script>

        <script src="js/transaction_history_slide.js"></script>
        <script src="js/transaction_history_show.js"></script>
        <script src="js/transaction_history_showhistory.js"></script>
    </head>
    <body id='main'>        
        <div id="box">
            <?php include_once 'subheader.php' ?>
            <div id="content">
                <form name="transactionForm" onsubmit="return validateForm(this);" action="" method="post">
                    <?php $fieldGen->display($fmat); ?>
                    <input type='submit' name='update' id='update' value='Update'>
                </form>                
                <!--<button onclick="setReadonly('data',false)">Edit</button>
                <button onclick="setReadonly('data',true)">Cancel</button>-->                
            </div><!-- end content!-->
        </div><!-- end box -->
        
        <div class="slide-out-div">
            <?php //include_once 'modhistory.php' ?>
        </div><!-- end slide out-->        
        
        <div id="sidebar">
            <?php //include_once 'sidebar.php' ?>
        </div><!-- end sidebar-->
    </body>
</html>

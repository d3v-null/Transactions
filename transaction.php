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

$fieldGen = new FieldGen();
$fieldGen->parse_metadata(DB_NAME, $page_table);
$fieldGen->lbls = array(
    'TransactionDate'   => 'Date of Transaction',
    'PaymentDate'       => 'Date of Payment',
    'ResponsibleParty'  => 'Party responsible for transaction',
    'AssociatedParty'   => 'Party associated with transaction',
    'Inflow'            => 'Payment Type',
    'StatusID'          => 'Status'
);
$fieldGen->add_rule(
    'StatusID',
    new FieldRule(
        'A valid status must be selected',
        function($s){
            $sql = "SELECT ID FROM Status WHERE ID =".$s;
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
            return $a>0;
        }
    )
);
$fieldGen->add_rule(
    'Inflow',
    new FieldRule(
        'A valid payment type must be chosen',
        function($a){
            return in_array($a, [1,2]);
        }
    )
);
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


if(key_exists('new', $_POST) or key_exists('new', $_GET)){ //If new button was pressed
    if (!$user->isTreasurer()){
        echo "<script>alert('You must have treasurer privileges to create a transaction')</script>";
    } else {                
        $fieldGen->vals['ModificationDate'] = date('Y-m-d h:m:s');
        $fieldGen->vals['ModificationPersonID'] =
            (isset($_SESSION['loginid']))?$_SESSION['loginid']:die("No login available");
        $result = mysql_query("SELECT MAX(TransactionID) AS ID FROM History") or die(mysql_error());
        $newID = mysql_fetch_array($result)['ID'] + 1;
        $fieldGen->vals['TransactionID'] = $newID;
        $fieldGen->vals['Description'] = "New Transaction (".$newID.")";
        //messy 
        $fieldGen->vals['StatusID'] = 1; 
        $fieldGen->vals['Inflow'] = 1;
        
        //might not be the best way of doing this   
        if( $fieldGen->validate() ){
            $fieldGen->mysql_insert();
            $id = mysql_insert_id();
            redirect("transaction.php?id=".$id);
        } else {
            FieldGen::exit_gracefully("could not create a new transaction");
        }
    }
} else {    
    if(key_exists('id', $_GET)){
        $id=$_GET["id"];
    } else {
        FieldGen::exit_gracefully("No ID Specified");
    }
    if(key_exists('update',$_POST)){ // If update button was pressed
        if (!$user->isTreasurer()){
            echo "<script>alert('You must have treasurer privileges to modify a transaction')</script>";
        } else {
            $fieldGen->parse($_POST);
            $fieldGen->vals['ModificationDate'] = date('Y-m-d h:m:s');
            $fieldGen->vals['ModificationPersonID'] =
                (isset($_SESSION['loginid']))?$_SESSION['loginid']:die("No login available");
            $fieldGen->vals['Amount'] *= 100;
            if( $fieldGen->validate() ){
                $fieldGen->mysql_insert();
                $id = mysql_insert_id();
                redirect("transaction.php?id=".$id);
            } else {
                echo "<script>alert('could not modify transaction')</script>";
            }
        }
    } else {
        $fieldGen->parse(FieldGen::fetch($page_table, $id));
    }
}

//generate status options
$rslt = mysql_query("SELECT * FROM Status") or die(mysql_error());
$sopts = array( 0 => '-- select --' );
while($row = mysql_fetch_array($rslt))
{
    $sopts[$row['ID']] = $row['Name'];
}

//Inflow options
$iopts = array( 0 => '-- select --', 'inflow', 'outflow' );

$scats = array();
$rslt = mysql_query("
    SELECT 
        Category.Name AS cname, 
        Subcategory.Name AS scname, 
        Subcategory.ID AS scid
    FROM Subcategory LEFT JOIN Category ON Subcategory.CategoryID = Category.ID");
while($row = mysql_fetch_array($rslt)){
    $scats[$row['scid']] = [$row['cname'], $row['scname']];
}

//echo serialize($fieldGen->meta);
?>
<!DOCTYPE html>
<html>
    <head>
        <title><?php echo $page_title?></title>
        <link rel="stylesheet" type="text/css" href="css/style2.css">
        <link rel="stylesheet" type="text/css" href="css/styling.css">

        <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.3/jquery.min.js" type="text/javascript"></script>
        <script src="js/jquery.tabSlideOut.v1.3.js"></script>

        <!--<script src="js/transaction_history_slide.js"></script>-->
        <script src="js/transaction_history_show.js"></script>
        <script src="js/transaction_history_showhistory.js"></script>
        <!-- local css -->
        <style>
            .fieldgenlist li{
                display: inline;
                list-style-type: none;
                padding-right: 20px;
            }
            .form-error{
                color:red;
                align:center;
            }
        </style>


    </head>
    <body id='main'>
        <div id='box'>
            <?php include_once 'subheader.php' ?>
                <form name="transactionForm" onsubmit="return validateForm(this);" action="transaction.php?id=<?php echo $id; ?>" method="post">
                    <div>
                        <?php
                        echo $fieldGen->display( array(
                            'Description' => FieldGen::textFormat(['FieldGen','fieldList']),
                            'StatusID'    => FieldGen::optionFormat($sopts, ['FieldGen','fieldList']),
                        ) );
                        ?>
                    </div>
                    <table>
                        <?php
                        echo $fieldGen->display( array(
                            'TransactionDate'   => FieldGen::inputFormat('date', ['FieldGen','fieldRow']),
                            'PaymentDate'       => FieldGen::inputFormat('date', ['FieldGen','fieldRow']),
                            'ResponsibleParty'  => FieldGen::inputFormat('text', ['FieldGen','fieldRow']),
                            'AssociatedParty'   => FieldGen::inputFormat('text', ['FieldGen','fieldRow']),
                            'Amount'    => function ($id, $lbl, $val, $rqd, $err){
                                $fld = "<input name='".$id."' type='text' value='". $val/100 ."' ".(($rqd)?"required":"").">";
                                $lbc = "<label for ='".$id."'>".$lbl.(($rqd)?"*":"")."</label>";
                                return FieldGen::fieldRow($id, $lbc, $fld, $err);
                            },
                            'Inflow'    => FieldGen::optionFormat($iopts, ['FieldGen','fieldRow']),
                        ) );
                        ?>
                     </table>
                    <div>
                        <?php
                        echo $fieldGen->display( array(
                            'Comment' => FieldGen::textFormat(['FieldGen','fieldList']),
                        ) );
                        ?>
                    </div>
                    <div>
                        
                    </div>    
                    <input type='hidden' name='TransactionID' value=<?php echo $fieldGen->vals['TransactionID']; ?>>
                    <input type='submit' name='update' value='Update'>
                    <button onclick="setReadonly('data',false)">Edit</button>
                </form>
                <div id='categories'>
                    <?php //include_once 'assoc_categories.php' ?>
                </div><!-- end sidebar-->                
                
                <!--<button onclick="setReadonly('data',true)">Cancel</button>-->
        </div><!-- end box -->

        <div class='slide-out-div'>
            <?php include_once 'modhistory.php' ?>
        </div><!-- end slide out-->

        <div id='sidebar'>
            <?php include_once 'sidebar.php' ?>
        </div><!-- end sidebar-->
        
    </body>
</html>

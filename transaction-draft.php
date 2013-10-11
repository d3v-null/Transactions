<?php

$page_title = 'Transaction Details';
$page_table = 'history';

require_once 'includes/transaction_setup.php';

require_once 'includes/config.php';
$user = new User();
if(!$user->loggedIn()){
    redirect('index.php');
}

require_once 'metaform.class.php';

//die if no history ID specified
$id=(key_exists('id', $_GET)) ? $_GET["id"] : die("No History ID specified");

//Check ID is valid
$fetch = MetaForm::fetch($page_table, $id);

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
$metaform = new MetaForm( MetaForm::metaTable(DB_NAME, $page_table) );
$metaform->pars['ModificationDate'] = date();
$metaform->vlds = Array(
    'Description' =>
        function($d){
            if($d == "") return 'Description must be unique and not empty';
            $sql = "SELECT History.Description FROM (
                        SELECT TransactionID, Max(ModificationDate) AS ModificationDate
                        FROM History
                        GROUP BY TransactionID
                    ) AS Latest
                    INNER JOIN History ON Latest.TransactionID = History.TransactionID
                    AND Latest.ModificationDate = History.ModificationDate
                    WHERE History.Description = ".$d;
            $result = mysql_query($sql) or die(mysql_error());
            if($result) return 'Description must be unique';
            // return Null;
        },
    'StatusID' => 
        function($s){
            if($s == 0) return 'Please choose a status';
            $sql = "SELECT StatusID FROM Status WHERE StatusID =".$s;
            $result = mysql_query($sql) or die(mysql_error());
            if(!$result) return 'Please choose a valid status';
            // return Null;
        },
    'Amount'=>
        function($a){
            if(!is_numeric($a)) return 'Amount must be numeric';
            if($a < 0) return 'Amount must be positive';
        },
);

// If update button was pressed
if(!empty($_POST) && isset($_POST['update']))
{
    if (!$user->isTreasurer()){
        echo "<script>alert('You must have treasurer privileges to modify a transaction')</script>";
    } else { 
        $metaform->$pars['TransacionID'] = $fetch['TransactionID'];
        $metaform::parse($_POST);
        
        $cols = implode(", ", array_keys($metaform->pars));
        $vals = implode(", ", array_values($metaform->pars));
        
        $sql =  "INSERT INTO history (".
                    $cols.
                ") VALUES (".
                    $vals.
                ") ";
        
        IF(debug) echo($sql);
        //mysql_query($sql) or die(mysql_error());
        //$id = mysql_insert_id();
    }
} else {
    $metaform->pars($fetch);
    $metaform::validate();
}

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
        <div class="slide-out-div">
            <h3>Transaction History:</h3>
             <?php
                $sql = "SELECT ID, ModificationDate, ModificationPersonID FROM History WHERE TransactionID = ".$fetch['TransactionID'].
                        "ORDER BY ModificationDate ASC";
                $result = mysql_query($sql) or die(mysql_error());
                echo "<ul>";
                while($row = mysql_fetch_array($result)){
                    echo "<li class='history' id='".$row['ID']."' onclick='showHistory(this.id)'>".$row['ModificationPersonID']." - ".$row['ModificationDate']."</li>";
                }    
            ?>
        </div><!-- end slide out-->
        <div id="box">
            <?php include 'subheader.php' ?>
            <div id="content">
                <form name="transactionForm" onsubmit="return validateForm(this);" action="transaction.php" method="post">
                    <?php
                    
                    $fmat = array(
                        'Description'=>MetaClass::InputFormat('text'),
                        // 'Status'=>,
                        'TransactionDate'=>MetaClass::InputFormat('datetime'),
                        'PaymentDate'=>MetaClass::InputFormat('datetime'),
                        'ResponsibleParty'=>MetaClass::InputFormat('text'),
                        'AssociatedParty'=>MetaClass::InputFormat('text'),
                        // 'Amount'=>MetaClass::InputFormat('text'),
                        // 'Inflow'=>,
                        'Comment'=>MetaClass::InputFormat('text'),
                    );                    
                    
                    $metaform::display($fmat);
                    ?>
                    <!--<table class = "formatted">
                        <tr>
                            <td class="transactionTitle">
                                Description
                            </td>
                            <td>
                                
                            </td>
                            <td> 
                                <div class="transactionTitle">
                                    Status:
                                </div>
                                <select id="Status" name="Status" disabled="disabled">
                                    <option value=""></option>
                                    <?php
                                        $sql = "SELECT * FROM Status";
                                        $statusIDs = mysql_query($sql ) or die(mysql_error());
                                        while($statusRow = mysql_fetch_array($statusIDs))
                                        {
                                            $sel = ($statusRow['ID']==$st)?"selected":"";
                                            echo "<option value=".$row['ID']." ".$sel." >".$row['Name']."</option>";
                                        }
                                    ?>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="4" class = "spaceBelow">
                                <textarea class="data" name="Description" readonly="readonly"><?=$row['Description'];?></textarea>
                            </td>
                        </tr>
                        <tr>
                            <td class = "transactionTitle">
                                Transaction Date*:
                            </td>
                            <td>
                                <input type="datetime" class="data" name="TransactionDate" size="12" value="<?=$row['TransactionDate'];?>"readonly="readonly">
                            </td>
                            <td class = "transactionTitle col2">
                                Amount*:
                            </td>
                            <td>
                                <input type="text" class="data" name="Amount" id="Amount" size="8"  value="<?=$row['Amount'];?>" readonly="readonly">
                            </td>
                        </tr>
                        <tr>
                            <td class = "transactionTitle">
                                Date of receipt/payment*:
                            </td>
                            <td>
                                <input type="datetime" class="data" name="PaymentDate" value="<?=$row['PaymentDate'];?>"size="12" readonly="readonly">
                            </td>
                            <td class = "transactionTitle col2">
                                Type*:
                            </td>
                            <td>
                                <?php
                                    $checked = ($row['Inflow'] == '1') ? "checked=\"checked\"" : "";
                                    $checked2 = ($checked == "") ? "checked=\"checked\"" : "";
                                ?>
                                <input type="radio" class="data" name="Type" value="in" disabled="disabled" <?=$checked;?>>Inflow <br>
                                <input type="radio" class="data" name="Type" value="out" disabled="disabled" <?=$checked2;?>>Outflow<br>
                            </td>
                        </tr>
                        <tr>
                            <td class = "transactionTitle">
                                Responsible*:
                            </td>
                            <td>
                                <input type="text" class="data" name="ResponsibleParty" value="<?=$row['ResponsibleParty'];?>"size="12" readonly="readonly">
                            </td>
                        </tr>
                        <tr>
                            <td class = "transactionTitle spaceBelow">
                                Associated person:
                            </td>
                            <td>
                                <input type="text" class="data" name="AssociatedParty" value="<?=$row['AssociatedParty'];?>"size="12" readonly="readonly">
                            </td>
                        </tr>
                        <tr>
                          <td class = "transactionTitle">
                            Comment:
                          </td>
                        </tr>
                        <tr>
                            <td colspan = "2">
                                <textarea cols="20" class="data" name="Comment" readonly="readonly"><?=$row['Comment'];?></textarea>
                            </td>
                        </tr>
                    </table>
                    -->
                    <input type='submit' name='update' id='update' value='Update'>
                </form>
                
                <button onclick="setReadonly('data',false)">Edit</button>
                <!--<button onclick="setReadonly('data',true)">Cancel</button>-->
                
            </div><!-- end content!-->
        </div><!-- end box -->
        <div id="sidebar">
        <?php include_once("sidebar.php")?>
        </div><!-- end sidebar-->
    </body id='main'>
</html>

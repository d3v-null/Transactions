<?php
    $page_title = 'Transaction Details';
    require_once 'includes/transaction_setup.php';
    require_once 'includes/config.php';

    $user = new User();
    if(!$user->loggedIn()){
        redirect('index.php');
    } 
    
    //If delete button was pressed
    if(!empty($_POST) && key_exists('delete', $_POST)){
        if (!$user->isTreasurer()){
            echo "<script>alert('You must have treasurer privileges to create a transaction')</script>";
        } else { 
            //Do delete things
            
        }
    }
    
    //ignore this
    
    // $PARS = Array(
        // 'id' => (isset($_GET['st']))?$_GET['st']:0,   //TransactionID
        // 'ds' => 
        // 'cm' =>
        // 'rd' =>
        // 'td' => 
        // 'pd' =>
        // 'md' =>
        // 'rp' =>
        // 'ap' =>
        // 'st' => (isset($_POST['st']))?$_POST['st']:0,   //Status
        // 'am' =>
        // 'if' =>
    // }

    // remove single and double quotes so no errors are thrown with the sql
    function removeQuotes($string)
    {
        $string = str_replace("'","\'", $string);
        return str_replace("\"", "\\\"", $string);
    }
    
    // If update button was pressed
    if(isset($_POST['update']))
    {
        if (!$user->isTreasurer()){
            echo "<script>alert('You must have treasurer privileges to modify a transaction')</script>";
        } else {       
            $sql =  "INSERT INTO History ( 
                        TransactionID, 
                        Description, 
                        Comment, 
                        ModificationDate, 
                        TransactionDate,
                        PaymentDate, 
                        ResponsibleParty, 
                        AssociatedParty, 
                        Amount, 
                        Inflow, 
                        StatusID
                     )".
                    "SELECT".
                        "'" . $_GET['id'] . "', ".
                        "'" . removeQuotes($_POST['Description']) . "', ".
                        "'" . removeQuotes($_POST['Comment']) . "', " .
                        "CURRENT_TIMESTAMP,".
                        "'" . $_POST['TransactionDate'] . "', ".
                        "'" . $_POST['PaymentDate'] . "', ".
                        "'" . $_POST['ResponsibleParty'] . "', ".
                        "'" . $_POST['AssociatedParty'] . "', ".
                        "'" . $_POST['Amount'] . "', ".
                        "'" .  ($_POST['Type'] == "in") . "', ".
                        "'" . $_POST['Status'] . "' ".
                    "FROM History ". 
                    "WHERE ID = '" . $_GET['id'] . "'" ; 
            IF(debug) echo($sql);
            mysql_query($sql) or die(mysql_error());
        }
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
                $sql = "SELECT ID FROM History WHERE TransactionID='" . $_GET['id'] . "'";
                $idResult = mysql_query($sql) or die(mysql_error());
                while($idResultRows = mysql_fetch_assoc($idResult))
                {
                    // echo $idResultRows['ID'];
                    $sql = "SELECT ModificationDate FROM HISTORY ".
                        "WHERE ".
                        "ID = '". $idResultRows['ID'] ."'".
                        "ORDER BY ModificationDate ASC";
                    $result = mysql_query($sql) or die(mysql_error());
                
                    while($row = mysql_fetch_assoc($result))
                    {
                        echo "<ul>";
                        echo"<li><div class='history' id='" . $idResultRows['ID'] . "' onclick='showHistory(this.id)'>" . $row['ModificationDate'] . "</div></li>";
                        echo"</ul>";
                        //echo $row['ModificationDate'] ."______".  $row['ID'];
                        print "<br>";
                    }
                }
            ?>
        </div><!-- end slide out-->
        <div id="box">
            <?php include 'subheader.php' ?>
            <div id="content">
                <?php
                    $sql = "SELECT * FROM History WHERE ID='" . $_GET['id'] . "'";
                    $result = mysql_query($sql ) or die(mysql_error());
                    $row = mysql_fetch_assoc($result);
                    $statusID = intval($row['StatusID']);
                ?>	
                <form name="transactionForm" onsubmit="return validateForm(this);" action="transaction.php" method="post">
                    <table class = "formatted">
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
                                            if(intval($statusRow['ID']) == $statusID)
                                                echo "<option value=" . $statusRow['ID'] . " selected='selected'>" . $statusRow['Name'] . "</option>";
                                            else
                                                echo "<option value=" . $statusRow['ID'] . " >" . $statusRow['Name'] . "</option>";
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
                    <input type="submit" name="update" id="update" value="Update">
                    <input type='submit' name='delete' value='Delete'>
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

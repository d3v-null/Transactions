<?php
    $page_title = 'Transaction History';
    require_once 'includes/transaction_setup.php';
    require_once 'includes/config.php';

    $user = new User();
    if(!$user->loggedIn()){
        redirect('index.php');
    }
        
    $subcats= array();
    $rslt = mysql_query("SELECT ID, Name FROM Subcategory");
    while($row = mysql_fetch_array($rslt)){
        $subcats[$row['ID']]=$row['Name'];
    }

    $pars = Array(
        'ts' => (isset($_GET['ts']))?$_GET['ts']:0,     //Transaction offset
        'tn' => (isset($_GET['tn']))?$_GET['tn']:10,    //transactions / page
        'oc' => (isset($_GET['oc']))?$_GET['oc']:1,     //Order-by column
        'od' => (isset($_GET['od']))?$_GET['od']:0,     //Order direction
        'kw' => (isset($_GET['kw']))?$_GET['kw']:"",    //Keywords
        'fd' => (isset($_GET['fd']))?$_GET['fd']:"",    //From date
        'td' => (isset($_GET['td']))?$_GET['td']:"",    //To date
        'st' => (isset($_GET['st']))?$_GET['st']:0,     //Status
        'sc' => (isset($_GET['sc']))?$_GET['sc']:array_keys($subcats), //subcategories selected
        'fs' => (isset($_GET['fs']))?$_GET['fs']:0      //Filter by subcategories
    );

    $cols = Array(
        Array('TransactionID', function($row){return $row['TransactionID'];}),
        Array('TransactionDate', function($row){return $row['TransactionDate'];}),
        Array('Description', function($row){return $row['Description'];}),
        Array('Status', function($row){return $row['Status'];}),
        Array('Amount', function($row){return $row['Amount'] / 100;}),
        Array('', function($row){return "<a id='edit' href='transaction.php?id=".$row['ID']."'>Edit</a>";}),
    );
    
    $lbls = [
        'TransactionID'   => 'ID',
        'TransactionDate' => 'Date'
    ];
        

    $ords = Array(
        Array('Descending', 'DESC'),
        Array('Ascending', 'ASC'),
    );

    $view = Array(2,5,10,20);

    //process pagination
    if(isset($_GET['pag'])){
        switch($_GET['pag']){
            case 'First':
                $pars['ts']=0;
                break;
            case 'Previous':
                $pars['ts']-=$pars['tn'];
                break;
            case 'Next':
                $pars['ts']+=$pars['tn'];
                break;
            case 'Last':
                $pars['ts']=PHP_INT_MAX;
                break;
            default:
                die('page button not configured correctly');
        }
    }
?>

<!DOCTYPE html>
<html>
    <head>
        <title><?php echo $page_title?></title>
        <meta charset="utf-8"/>
        <!--[if lt IE 9]>
            <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
        <![endif]
        -->
        <link rel="stylesheet" type="text/css" href="css/style2.css">
        <link rel="stylesheet" type="text/css" href="css/styling.css">
        <script src="js/expander.js"></script>
    </head>

    <body id="main">
      <form method="get" action=""> 
        <div id="box">
            <?php include 'subheader.php' ?>
                <div id="search">
                    <div id="title">Search<img hspace="10px" width="15px" height="15px" src="images/search_icon.png"/></div>
                    <input type="submit" name="search" value="Update">

                    <table id="options-basic">
                        <tr>
                            <td><label for='kw'>Keywords</label></td>
                            <td><label for='fd'>From date</label></td>
                            <td><label for='td'>To date</label></td>
                        </tr>
                        <tr>
                            <td><input type='text' name='kw' value='<?php echo $pars['kw']?>'></td>
                            <td><input type='date' name='fd' value='<?php echo ($pars['fd']) ?>'></td>
                            <td><input type='date' name='td' value='<?php echo ($pars['td']) ?>'></td>
                        </tr>
                    </table>
                    <table id="options-advanced">
                        <tr>
                            <td><label for='st'>Status</label></td>
                            <td><label for='oc'>Order by</label></td>
                            <td><label for='od'>Order direction</label></td>
                            <td><label for='tn'>Transactions per page</label></td>
                            <td><label for='fs'>Filter by Subcategories</label></td>
                        </tr>
                        <tr>
                            <td>
                                <select name="st">
                                    <option value=0>-- Select --</option>
                                    <?php
                                        $statuses = mysql_query("SELECT * FROM Status") or die(mysql_error());
                                        while($row = mysql_fetch_array($statuses)){
                                            $sel = ($row['ID']==$pars['st'])?"selected":"";
                                            echo "<option value=".$row['ID']." ".$sel." >".$row['Name']."</option>";
                                        }
                                    ?>
                                </select>
                            </td>
                            <td>
                                <select name="oc">
                                    <?php
                                        foreach($cols as $k => $v){
                                            $sel = ($k==$pars['oc'])?"selected":"";
                                            echo "<option value=".$k." ".$sel." >".$v[0]."</option>";
                                        }
                                    ?>
                                </select>
                            </td>
                            <td>
                                <select name='od'>
                                    <?php
                                        foreach($ords as $k => $v){
                                            $sel = ($k==$pars['od'])?"selected":"";
                                            echo "<option value=".$k." ".$sel." >".$v[0]."</option>";
                                        }
                                    ?>
                                </select>
                            </td>
                            <td>
                                <select name='tn'>
                                    <?php
                                        foreach($view as $v){
                                            $sel = ($v==$pars['tn'])?"selected":"";
                                            echo "<option value=".$v." ".$sel." >".$v."</option>";
                                        }
                                    ?>
                                </select>
                            </td>
                            <td>
                                <input name='fs' type='checkbox' <?php echo (($pars['fs'])?"checked":"") ?> >
                            </td>
                        </tr>
                    </table>
                    <a id="search-expander">Show advanced options</a>
                    <a id="search-hider" style="display:none">Hide advanced options</a>
                </div><!-- end search-->

                <?php
                    //select the transactions to display on the page
                    $whrs = array(1);
                    if ($pars['st'] != 0) {
                        array_push($whrs, "StatusID = ".$pars['st']);
                    }
                    if ($pars['fd'] != Null AND $pars['td'] != Null) {
                        array_push($whrs, "TransactionDate BETWEEN ".$pars['fd']." AND ".$pars['td']);
                    }
                    if ($pars['kw'] != "") {
                        array_push($whrs, "History.Description LIKE '%".$pars['kw']."%' OR Comment LIKE '%".$pars['kw']);
                    }
                    
                    if($pars['fs']){
                        $kernel = "
                            (
                                SELECT DISTINCT Transaction.ID
                                FROM 
                                    Categorization 
                                    INNER JOIN Transaction
                                ON Categorization.TransactionID = Transaction.ID
                                WHERE Categorization.SubcategoryID IN (".implode(", ",$pars['sc']).")
                            ) 
                            AS Transaction";
                    } else {
                        $kernel = "Transaction";
                    }

                    $search="
                        SELECT
                            History.ID                    AS ID,
                            History.TransactionID         AS TransactionID,
                            DATE(History.TransactionDate) AS TransactionDate,
                            History.Description           AS Description,
                            History.Amount                AS Amount,
                            Status.Name                   AS Status,
                            Status.ID                     AS StatusID
                        FROM 
                            (
                                SELECT 
                                    Transaction.ID,
                                    max(ModificationDate) AS ModificationDate
                                FROM 
                                    ".$kernel."
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
                        WHERE ".implode(" AND ", $whrs)." ";
                    //echo $search;
                    $result = mysql_query("SELECT COUNT(*) AS Count FROM (".$search.") AS T") or die(mysql_error());;
                    $count = mysql_fetch_array($result)['Count'];
                    $pars['ts'] = max(0, min($count-$pars['tn'],$pars['ts']));
                    $post = "ORDER BY ".$cols[$pars['oc']][0]." ".$ords[$pars['od']][1].
                            " LIMIT ".$pars['tn']." OFFSET ".$pars['ts'].";";
                    $page = mysql_query($search.$post) or die(mysql_error());
                    

                ?>

                <table id="transaction-list" summary = "List of Transactions">
                    <thead>
                        <?php
                            echo "<tr>";
                            foreach($cols as $v){
                                echo "<th>".((isset($lbls[$v[0]]))?$lbls[$v[0]]:$v[0])."</th>";
                            }
                            echo "</tr>";
                        ?>
                    </thead>
                    <tbody>
                    <?php
                        while($row = mysql_fetch_array($page))
                        {
                            echo "<tr>";
                            foreach($cols as $v){
                                echo "<td>".$v[1]($row)."</td>";
                            }
                            echo "</tr>";
                        }
                    ?>
                    </tbody>
                </table>
                <input type='hidden' name='ts' value=<?php echo $pars['ts']?>>

                <div id="pagination">
                    <input type="submit" name="pag" value="First">
                    <input type="submit" name="pag" value="Previous">
                    <?php
                        echo "Displaying transactions ".$pars['ts']." to ".($pars['ts']+$pars['tn'])." of ".$count." results.";
                    ?>
                    <input type="submit" name="pag" value="Next">
                    <input type="submit" name="pag" value="Last">
                </div><!-- end pagination -->
            
        </div><!-- end box -->
      </form>
        <div id="sidebar">
            <?php
                $showBoxes = true;
                $checked = $pars['sc'];
                include_once("sidebar.php");
            ?>
        </div>
    </body>
</html>

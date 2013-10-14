<?php
    $page_title = 'Transaction History';
    require_once 'includes/transaction_setup.php';
    require_once 'includes/config.php';

    $user = new User();
    if(!$user->loggedIn()){
        redirect('index.php');
    }

    $pars = Array(
        'ts' => (isset($_GET['ts']))?$_GET['ts']:0,     //Transaction offset
        'tn' => (isset($_GET['tn']))?$_GET['tn']:20,    //transactions / page
        'oc' => (isset($_GET['oc']))?$_GET['oc']:1,     //Order-by column
        'od' => (isset($_GET['od']))?$_GET['od']:0,     //Order direction
        'kw' => (isset($_GET['kw']))?$_GET['kw']:"",    //Keywords
        'fd' => (isset($_GET['fd']))?$_GET['fd']:"",    //From date
        'td' => (isset($_GET['td']))?$_GET['td']:"",    //To date
        'st' => (isset($_GET['st']))?$_GET['st']:0,     //Status
    );

    $cols = Array(
        Array('TransactionID', function($row){return $row['TransactionID'];}),
        Array('TransactionDate', function($row){return $row['TransactionDate'];}),
        Array('Description', function($row){return $row['Description'];}),
        Array('Status', function($row){return $row['Status'];}),
        Array('Amount', function($row){return $row['Amount'] / 100;}),
        Array('', function($row){return "<a id='edit' href='transaction.php?id=".$row['ID']."'>Edit</a>";}),
    );

    $ords = Array(
        Array('Descending', 'DESC'),
        Array('Ascending', 'ASC'),
    );

    $view = Array(2,5,20,50,100);

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
        <div id="box" class="bordered">
        <?php include 'subheader.php' ?>

        <form method="get" action="search.php" class="content">
            <div class="bordered">
                <h2>Search</h2>
                <input type="submit" name="search" value="Update">

                <table id="options-basic">
                    <tr>
                        <td>Keywords</td>
                        <td>From date</td>
                        <td>To date</td>
                    </tr>
                    <tr>
                        <td><input type='text' name='kw' value='<?php echo $pars['kw']?>'></td>
                        <td><input type='date' name='fd' value='<?php echo ($pars['fd']) ?>'></td>
                        <td><input type='date' name='td' value='<?php echo ($pars['td']) ?>'></td>
                    </tr>
                </table>
                <table id="options-advanced">
                    <tr>
                        <td>Status</td>
                        <td>Order by</td>
                        <td>Order direction</td>
                        <td>Transactions per page</td>
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
                    </tr>
                </table>
                <a id="search-expander">Show advanced options</a>
                <a id="search-hider" style="display:none">Hide advanced options</a>
            </div><!-- end bordered-->

            <?php
                //select the transactions to display on the page
                $whr = "";
                if ($pars['st'] != 0) {
                    $whr = $whr . "StatusID = ".$pars['st']."\nAND ";
                }
                if ($pars['fd'] != Null AND $pars['td'] != Null) {
                    $whr = $whr . "TransactionDate BETWEEN ".$pars['fd']." AND ".$pars['td']."\nAND ";
                }
                if ($pars['kw'] != "") {
                    $whr = $whr . "History.Description LIKE '%".$pars['kw']."%' OR Comment LIKE '%".$pars['kw']."%'\nAND ";
                }
                if (substr($whr,-4) == "AND ") {
                    $whr = substr($whr,0,strlen($whr)-4);
                }
                if ($whr != "") {
                    $whr = "WHERE " . $whr;
                }

                $search="
                    SELECT
                        History.ID As ID,
                        History.TransactionID AS TransactionID,
                        DATE(History.TransactionDate) AS TransactionDate,
                        History.Description AS Description,
                        History.Amount AS Amount,
                        Status.Name AS Status,
                        Status.ID AS StatusID
                    FROM (
                        SELECT TransactionID, Max(ModificationDate) AS ModificationDate
                        FROM History
                        GROUP BY TransactionID
                    ) AS Latest
                    INNER JOIN History ON Latest.TransactionID = History.TransactionID
                    AND Latest.ModificationDate = History.ModificationDate
                    INNER JOIN Status ON Status.ID = History.StatusID ".$whr;

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
                            echo "<td>".$v[0]."</td>";
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
            <input type='hidden' name='ts' value=<?php echo $pars['ts']?>>;

            <div id="pagination">
                <input type="submit" name="pag" value="First">
                <input type="submit" name="pag" value="Previous">
                <?php
                    echo "Displaying transactions ".$pars['ts']." to ".($pars['ts']+$pars['tn'])." of ".$count
                ?>
                <input type="submit" name="pag" value="Next">
                <input type="submit" name="pag" value="Last">
            </div><!-- end pagination -->
        </form>
    </div><!-- end box -->
    <div id="sidebar" class="bordered">
        <?php include_once("sidebar.php")?>
    </div>
    </body>
</html>

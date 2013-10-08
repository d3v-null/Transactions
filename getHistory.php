<?php
$id = intval($_GET['id']);

$connection =mysql_connect("localhost","test","test") or die("Could not connect");

mysql_select_db("test") or die("Unable to select database");
 $sql = "SELECT ".
            "Description,". 
            "Comment,".
            "TransactionDate,".
            "PaymentDate,".
            "ResponsibleParty,".
            "AssociatedParty,".
            "Amount,".
            "Inflow,".
            "StatusID ".
            "FROM History ".
            "WHERE ID = '" . $id . "'";

$result = mysql_query($sql);

						// Connect to database
						$sql = "SELECT * FROM History WHERE ID='" . $_GET['id'] . "'";
						$result = mysql_query($sql, $connection) or die(mysql_error());
						$row = mysql_fetch_assoc($result);
						$statusID = intval($row['StatusID']);
?>
          <div id="deleteMe"> 
            <table class = "formatted">
              <!-- action="toMe.php" -->
              <form name="transactionForm" action="" method="post">
                <tr>
                  <td  colspan = "2" class = "transactionTitle">
                    Transaction Description
                  </td>
                  <td></td>
                  <td>              
                    <select id="Status" name="Status" disabled="disabled">
                      <option value=""></option>
                      <?php
                        $sql = "SELECT * FROM Status";
                        $statusIDs = mysql_query($sql, $connection) or die(mysql_error());
                        while($statusRow = mysql_fetch_array($statusIDs))
                        {
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
                    <input type="text" class="data" name="Amount" size="8"  value="<?=$row['Amount'];?>" readonly="readonly">
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
                <button type="Reset">Clear</button>
                <input name="update" type="submit" id="update" value="Update">

              </form>
              <button onclick="setReadonly('data',false)">Edit</button>
              <button onclick="setReadonly('data',true)">Cancel</button>
          </div> <!--delete end-->

 

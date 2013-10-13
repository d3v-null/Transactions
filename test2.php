<?php

include 'FieldGen.php';
$id=(key_exists('id', $_GET)) ? $_GET["id"] : die("No History ID specified");
$fetch = FieldGen::fetch($page_table, $id);
$fieldGen = new FieldGen();
$fieldGen->lbls = array(
    'TransactionDate'   => 'Date of Transaction', 
    'PaymentDate'       => 'Date of Payment',
    'ResponsibleParty'  => 'Party responsible for transaction',
    'AssociatedParty'   => 'Party associated with transaction',
    'Inflow'            => 'Payment Type',
    'StatusID'          => 'Status'
);
echo $fieldGen->lbls;
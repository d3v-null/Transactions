<?php

include 'FieldGen.php';

echo FieldGen::fieldList('derp', 'herp', 'asd', 'err');
//works
$fmt = FieldGen::inputFormat('text', ['FieldGen','fieldList']);
echo $fmt('derp', 'herp', 'asd', 'err');


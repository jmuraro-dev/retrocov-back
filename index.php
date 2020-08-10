<?php
//echo "Hello World";
echo date('Y-m-d');
echo "\n";
$conservationTime = 15; // in days
echo date("Y-m-d", strtotime("-".$conservationTime." days"));
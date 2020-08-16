<?php
//echo "Hello World";
echo sha1(date('Y-m-d H:i:s'));
echo "\n";
$conservationTime = 15; // in days
echo date("Y-m-d", strtotime("-".$conservationTime." days"));
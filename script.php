<?php

//sleep(4);

$fName = "act/inScript." . rand(50, 10000);
$fHandle = fopen($fName, 'w') or die("can't open file");
fclose($fHandle);

echo 'important work being done here, and a random number for you: => ' . rand(0, 1000) . ' <= ';
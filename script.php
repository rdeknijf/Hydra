<?php

//sleep(4);

$number = rand(0, 1000);

//$fName = "act/inScript." . rand(50, 10000);
//$fHandle = fopen($fName, 'w') or die("can't open file");
//fwrite($fHandle, $number);
//fclose($fHandle);

$options = getopt('', array('feed:', 'line:'));


echo " XXX====> " . $options['feed'] .  " <====XXX\n";
echo " XXX====> " . $options['line'] .  " <====XXX\n";
echo " XXX====>  $number  <====XXX random number for ya\n";
echo " and another line";




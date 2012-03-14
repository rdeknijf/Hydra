<?php

sleep(6);

$number = rand(0, 1000);

$options = getopt('', array('feed:', 'line:'));


echo " XXX====> " . $options['feed'] .  " <====XXX\n";
echo " XXX====> " . $options['line'] .  " <====XXX\n";
echo " XXX====>  $number  <====XXX random number for ya\n";
echo " and another line";




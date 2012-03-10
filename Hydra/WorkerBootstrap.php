<?php

namespace Hydra;

$fName = "act/preWorker." . rand(50, 1000);
$fHandle = fopen($fName, 'w') or die("can't open file");
fclose($fHandle);

spl_autoload_register();

$options = getopt('m:t:v:');

$worker = new Worker($options['m'],$options['t'],$options['v']);
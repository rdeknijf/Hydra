<?php

//namespace Hydra;

spl_autoload_register();

$options = getopt('m:t:v:');

$worker = new Hydra\Worker($options['m'],$options['t'],$options['v']);
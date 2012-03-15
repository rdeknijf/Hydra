<?php

function hydra_autoload($pClassName) {
    include(__DIR__ . "/" . $pClassName . ".php");
}

spl_autoload_register('hydra_autoload');

$options = getopt('m:t:v:');

$worker = new Hydra\Worker($options['m'], $options['t'], $options['v']);
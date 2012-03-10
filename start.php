<?php

namespace Hydra;

spl_autoload_register();

set_time_limit(10);

$start = microtime(true);



for ($i = 0; $i < 5; $i++) {

    $tasks[] = new Task(realpath(__DIR__ . '/script.php'), array('number' => 4));

}


$mother = new Factory();
$mother->addTasks($tasks);

$results = $mother->execute();

var_dump($results);

var_dump(microtime(true) - $start);
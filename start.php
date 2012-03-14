<?php

namespace Hydra;

spl_autoload_register();

set_time_limit(10);

$start = microtime(true);



for ($i = 0; $i < 5; $i++) {

    $task = new Task(realpath(__DIR__ . '/script.php'));

//    $task->addOption('--feed', '44');
//    $task->addOption('--line', '153');

    $tasks[] = $task;

}


$mother = new Factory(3);
$mother->addTasks($tasks);

$results = $mother->execute();

var_dump($results);

var_dump(microtime(true) - $start);
<?php

namespace Hydra;

spl_autoload_register();

$runtime = 60; //script.php sleeps for 6 seconds to illustrate parrallel nature of multiprocessing

set_time_limit($runtime);

$start = microtime(true);



for ($i = 0; $i < 5; $i++) {

    $task = new Task('dir');
//    $task = new Task('php ' . realpath(__DIR__ . '/script.php'));

//    $task->addOption('--feed', '44');
//    $task->addOption('--line', '153');

    $tasks[] = $task;

}


$mother = new Factory($runtime);
$mother->addTasks($tasks);

$results = $mother->execute();

var_dump($results);

var_dump(microtime(true) - $start);
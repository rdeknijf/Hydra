<?php

namespace Hydra;

spl_autoload_register();

set_time_limit(10);

$start = microtime(true);





//$dbFile = sys_get_temp_dir() . '/hydra.db';
//
//var_dump(fileperms($dbFile));
//var_dump(substr(sprintf('%o', fileperms($dbFile)), -4));
//
//exec('rm ' . $dbFile);
//
//die();




for ($i = 0; $i < 5; $i++) {

    $tasks[] = new Task(realpath(__DIR__ . '/script.php'));

}


//$tasks = array(
//    new Task(realpath(__DIR__ . '/script.php')),
//    new Task(realpath(__DIR__ . '/script.php')),
//    new Task(realpath(__DIR__ . '/script.php')),
//    new Task(realpath(__DIR__ . '/script.php')),
//);


$mother = new Factory();
$mother->addTasks($tasks);

$results = $mother->execute();

var_dump($results);

var_dump(microtime(true) - $start);
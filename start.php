<?php

namespace Hydra;

spl_autoload_register();


$tasks = array(
    new Task(realpath(__DIR__ . '/script.php')),
    new Task(realpath(__DIR__ . '/script.php')),
    new Task(realpath(__DIR__ . '/script.php')),
    new Task(realpath(__DIR__ . '/script.php')),
);




$mother = new Factory();
$mother->addTasks($tasks);

$results = $mother->execute();
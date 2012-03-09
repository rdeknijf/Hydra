<?php

namespace Hydra;

/**
 * Description of Mother
 *
 * @author Rutger de Knijf
 * @package Hydra
 */
class Factory {

    public $tasks;

    public function execute() {

        $start = microtime(true);



        //$memcache = new \Memcache;
        //$memcache->connect('localhost', 11211) or die("Could not connect");


        foreach ($this->tasks as $task) {

            //. add task to medium

            $medium = new SqliteMedium;

            $medium->addTask($task);

            //. dispatch worker


        }

        foreach ($this->tasks as $task) {

            exec('psexec -d php Hydra/WorkerBootstrap.php', $out);

            echo $out;

        }



        sleep(6);
        echo microtime(true) - $start;


        //return $output;
    }

//    private function executeTask(Task $task) {
//
//
//
//    }

    public function addTask(Task $task) {

        $this->tasks[$task->getGuid()] = $task;
    }

    public function addTasks($tasks) {

        foreach ($tasks as $task)
            $this->addTask($task);
    }

    public function execInBackground($cmd) {
        if (substr(php_uname(), 0, 7) == "Windows") {
            pclose(popen("start /B " . $cmd, "r"));
        } else {
            exec($cmd . " > /dev/null &");
        }
    }

}
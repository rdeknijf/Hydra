<?php

namespace Hydra;

/**
 * Description of Mother
 *
 * @author Rutger de Knijf
 * @package Hydra
 */
class Factory {

    /**
     * @var SqliteMedium The medium (Sqlite Database) used by this factory
     */
    private $medium;
    public $tasks;

    public function execute() {



        $sleep = 100000;

        $start = microtime(true);



        //$memcache = new \Memcache;
        //$memcache->connect('localhost', 11211) or die("Could not connect");


        foreach ($this->tasks as $task) {

            //. add task to medium
            $this->getMedium()->addTask($task);

            //usleep($sleep);
        }


        foreach ($this->tasks as $task) {

            //. dispatch worker

            $this->execInBackground('php Hydra/WorkerBootstrap.php -t ' . $task->getGuid());

            //usleep($sleep);

        }




        return $this->getResults();

        //echo microtime(true) - $start;


    }

    public function getResults() {

        $maxWaitSecs = 60;
        $waitedSecs = 0;
        $defSleepMSecs = 100000;


        while ($waitedSecs < $maxWaitSecs) {

            usleep($defSleepMSecs);

            $waitedSecs += ($defSleepMSecs / 1000000);

            $unresolvedCount = count($this->tasks);

            foreach ($this->tasks as $task) {

                $unresolvedCount -= $this->getMedium()->isTaskResolved($task);
            }

            if ($unresolvedCount == 0) {

                foreach ($this->tasks as &$task) {

                    $task = $this->getMedium()->getTask($task);
                }

                return $this->tasks;
            }
        }

        $dbFile = sys_get_temp_dir() . '/hydra.db';


        //var_dump(fileperms($dbFile));

        //exec('rm ' . sys_get_temp_dir() . '/hydra.db' );

        //usleep($defSleepMSecs);
        //$this->destroyMedium();
        return false;
    }

    private function destroyMedium() {

        $this->getMedium()->destroy();

    }


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

    /**
     *
     * @return SqliteMedium
     */
    private function getMedium() {

        if (!$this->medium)
            //$this->medium = new SqliteMedium;
            $this->medium = new MemcacheMedium;

        return $this->medium;
    }

}
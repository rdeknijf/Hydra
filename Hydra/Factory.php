<?php

namespace Hydra;

/**
 * Description of Mother
 *
 * @author Rutger de Knijf
 * @package Hydra
 */
class Factory {

    private $verbosity;

    private $logger;

    private $mediumType;

    /**
     * @var SqliteMedium The medium (Sqlite Database) used by this factory
     */
    private $medium;
    public $tasks;

    public function __construct($mediumType = 'Memcache') {

        $this->verbosity = 1;

        $this->mediumType = $mediumType;

        $this->log('Constructed');

    }

    public function execute() {

        $this->log('Executing');

        $sleep = 100000;

        $start = microtime(true);

        $taskCount = count($this->tasks);

        //$memcache = new \Memcache;
        //$memcache->connect('localhost', 11211) or die("Could not connect");


        foreach ($this->tasks as $task) {

            //. add task to medium
            $this->getMedium()->addTask($task);

            //usleep($sleep);
        }

        $this->log("Added $taskCount tasks to medium");


        foreach ($this->tasks as $task) {

            //. dispatch worker

            $this->execInBackground('php Hydra/WorkerBootstrap.php -m '. $this->mediumType .' -t ' . $task->getGuid() . ' -v 1');

            //usleep($sleep);

        }

        $this->log("Executed $taskCount workers");


        return $this->getResults();

        //echo microtime(true) - $start;


    }

    public function getResults() {

        $this->log('Started getResults()');

        $maxWaitSecs = 5;
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

            if (ctype_digit((string)$waitedSecs))
                $this->log("Waited for $waitedSecs seconds");

        }

        $this->log("Timeout of $maxWaitSecs seconds reached, aborting.");

        //$dbFile = sys_get_temp_dir() . '/hydra.db';


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

        if (!$this->medium) {

            $name = 'Hydra\\Medium\\' . $this->mediumType;

            $this->medium = new $name();

        }

        return $this->medium;
    }

    private function log($string) {

        if ($this->verbosity) {

            if (!$this->logger) {
                $this->logger = new Logger;
                $this->logger->log('-----------------------------------------');

            }

            $this->logger->log('Hydra Factory: ' . $string);

        }

    }

}
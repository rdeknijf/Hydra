<?php

namespace Hydra;

/**
 * Management class
 * You instantiate this class and add tasks to it,
 * the if you execute() this class it will execute all tasks
 *
 * @author Rutger de Knijf
 * @package Hydra
 */
class Factory {


    private $maxWaitSecs;


    /**
     * Level of verbosity
     * 0 = off
     * 1 = on
     * 2 = not implemented atm
     * @var int
     */
    private $verbosity;


    /**
     * Logger class, used by factory to log messages to system log
     * @var Logger
     */
    private $logger;


    /**
     * String defining the medium used as the intermediary, can be Memcache or Sqlite3 at the moment
     * @var type
     */
    private $mediumType;

    /**
     * @var SqliteMedium The medium (Sqlite3 or Memcache) used by this factory
     */
    private $medium;

    /**
     * List of tasks currently registered by this Factory
     * @var type
     */
    public $tasks;

    public function __construct($maxWaitSecs = 5, $mediumType = 'Memcache') {

        $this->verbosity = 1;

        $this->mediumType = $mediumType;

        $this->maxWaitSecs = $maxWaitSecs;

        $this->log('Constructed');

    }

    /**
     * Executes all registered tasks
     * @return array of Hydra\Task Returns the tasks that were registered, in their resolved form
     */
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

            $this->execInBackground('php ' . __DIR__  . '/WorkerBootstrap.php -m '. $this->mediumType .' -t ' . $task->getGuid() . ' -v 1');

            //usleep($sleep);

        }

        $this->log("Executed $taskCount workers");


        return $this->getResults();

        //echo microtime(true) - $start;


    }


    /**
     * Waits for workers and returns the results when ready or returns false on timeout
     * @param int $maxWaitSecs Maximum number of seconds to wait for workers
     * @return array of Hydra/Task|false
     */
    public function getResults() {

        $this->log('Started getResults()');

        $waitedSecs = 0;
        $defSleepMSecs = 100000;


        while ($waitedSecs < $this->maxWaitSecs) {

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

        $this->log("Timeout of {$this->maxWaitSecs} seconds reached, aborting.");

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


    /**
     * Adds a single task to the Factory register
     * @param Task $task
     */
    public function addTask(Task $task) {

        $this->tasks[$task->getGuid()] = $task;
    }

    /**
     * Adds an array of tasks to the Factory register
     * @param array of Task $tasks
     */
    public function addTasks($tasks) {

        foreach ($tasks as $task)
            $this->addTask($task);
    }


    /**
     * Windows- and Linux-safe background executor
     * @param type $cmd
     */
    private function execInBackground($cmd) {
        if (substr(php_uname(), 0, 7) == "Windows") {
            pclose(popen("start /B " . $cmd, "r"));
        } else {
            exec($cmd . " > /dev/null &");
        }
    }

    /**
     * Returns the medium used, instantiates if neccessary
     * @return Medium\Base
     */
    private function getMedium() {

        if (!$this->medium) {

            $name = 'Hydra\\Medium\\' . $this->mediumType;

            $this->medium = new $name();

            if (!$this->medium) throw new \Hydra\Exception('Medium not instantiated');

        }

        return $this->medium;
    }

    /**
     * Logs a single string to the log
     * @param string $string
     */
    private function log($string) {

        if ($this->verbosity) {

            if (!$this->logger) {
                $this->logger = new Logger;
                $this->logger->log('-----------------------------------------');

            }

            $string = 'Hydra Factory: ' . $string;

            $this->logger->log($string);

            echo $string . "\n";

        }

    }

}
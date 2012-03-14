<?php

namespace Hydra;

/**
 * Description of Worker
 *
 * @author Rutger de Knijf
 * @package Hydra
 */
class Worker {

    private $mediumType;
    private $logger;
    private $verbosity;
    private $medium;

    public function __construct($mediumType = 'Memcache', $task_id = null, $verbosity = 0) {

        $this->verbosity = $verbosity;
        $this->mediumType = $mediumType;


        $this->log('Started');

        //. get task
        $task = $this->getMedium()->getTask($task_id);



        if ($task) {

            $this->log('Retrieved task, executing');

            //. execute
            $output = Array();

            $execString = 'php ' . $task->getScript() . $this->optToStr($task);

            $this->log('Retrieved task; executing "' . $execString . '"');

            try {

                exec($execString, &$output);

            } catch (Exception $exc) {

                echo $exc->getTraceAsString();

                $this->log('Worker exeception');

            }
            //. save results

            $this->log('Task finished, saving output');

            $task->setOutput($output);
            $task->setResolved();
            $this->getMedium()->resolveTask($task);

            //. die
        } else {

            $this->log('Did not retrieve task, aborting worker');
        }
    }

    private function getMedium() {



        if (!$this->medium) {

            $name = 'Hydra\\Medium\\' . $this->mediumType;

            $this->medium = new $name();
//var_dump($this->mediumType);
        }

        return $this->medium;
    }

    private function log($string) {

        if ($this->verbosity) {

            if (!$this->logger)
                $this->logger = new Logger;

            $this->logger->log('Hydra Worker : ' . $string);
        }
    }

    private function optToStr(Task $task) {

        $string = '';

        $options = $task->getOptions();

        if (is_array($options))
            foreach ($options as $key => $val) {

                if (!is_int($key))
                    $string .= ' ' . $key;

                $string .= ' ' . $val;
            }

        return $string;
    }

}
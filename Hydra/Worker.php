<?php

namespace Hydra;

/**
 * Description of Worker
 *
 * @author Rutger de Knijf
 * @package Hydra
 */
class Worker {

    private $medium;

    public function __construct($task_id = null) {

//        $fName = "act/inWorker." . rand(50, 1000);
//        $fHandle = fopen($fName, 'w') or die("can't open file");


        //. pick up task

//        fwrite($fHandle, " => $task_id <= ");
//        fwrite($fHandle, 'pre-get ');

        $task = $this->getMedium()->getTask($task_id);

//        fwrite($fHandle, 'post-get ');


        if ($task) {

//            fwrite($fHandle, 'task found');
//            fwrite($fHandle, serialize($task));
//
//            fwrite($fHandle, 'task dumped');

            //. execute it

            $output = Array();
            //$return_var = 'something';

            exec('php ' . $task->getScript(), &$output);

//            fwrite($fHandle, 'something');
//            fwrite($fHandle, implode(' ', $output));

            //. save results

            $task->setOutput($output);
            $task->setResolved();
            $this->getMedium()->resolveTask($task);

            //. die

        } else {

            //echo 'Did not receive task from Medium';
        }

//        fwrite($fHandle, 'end');
//
//        fclose($fHandle);

    }

    private function getMedium() {

        if(!$this->medium) $this->medium = new Medium\Memcache;

        return $this->medium;

    }

}
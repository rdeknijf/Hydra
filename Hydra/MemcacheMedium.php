<?php

namespace Hydra;

/**
 * Description of Medium
 *
 * @author Rutger de Knijf
 * @package Hydra
 */
class MemcacheMedium extends Medium {

    private $repo;

    /**
     *
     * @return \Memcache
     */
    private function getRepo() {

        if (!$this->repo) {

            $this->initRepo();

        }

        return $this->repo;

    }

    private function initRepo() {

        $this->repo = new \Memcache;
        $this->repo->connect('localhost', 11211) or die ("Could not connect");

    }



    public function addTask(Task $task) {

        $this->getRepo()->set($task->getGuid(), $task);

    }

    public function getTask($id) {



    }

    public function claimTask($worker_id) {



    }


}
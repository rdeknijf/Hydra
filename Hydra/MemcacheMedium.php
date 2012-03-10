<?php

namespace Hydra;

/**
 * Description of Medium
 *
 * @author Rutger de Knijf
 * @package Hydra
 */
class MemcacheMedium extends MediumBase {

    protected $repo;

    protected function initRepo() {

        $this->repo = new \Memcache;
        $this->repo->connect('localhost', 11211) or die ("Could not connect");

    }




    public function addTask(Task $task) {

        $this->getRepo()->set('id_'.$task->getGuid(), $task);
        $this->getRepo()->set('resolved_'.$task->getGuid(), false);

    }

    public function getTask($taskOrId) {

        $taskId = ($taskOrId instanceof Task) ? $taskId = $taskOrId->getGuid () : $taskOrId;

        return $this->getRepo()->get('id_'.$taskId);

    }

    public function isTaskResolved($taskOrId) {

        $id = ($taskOrId instanceof Task) ? $id = $taskOrId->getGuid () : $taskOrId;

        return $this->getRepo()->get('resolved_' . $id);

    }

    public function resolveTask(Task $task) {

        $this->getRepo()->set('id_'.$task->getGuid(), $task);
        $this->getRepo()->set('resolved_' . $task->getGuid(), true);

    }




}
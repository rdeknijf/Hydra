<?php

namespace Hydra;

/**
 * Description of SqliteMedium
 *
 * @author Rutger de Knijf
 * @package Hydra
 */
class SqliteMedium implements Medium {

    private $repo;

    public function addTask(Task $task) {

        $sql = sprintf("INSERT INTO tasks ( id, task ) VALUES ('%s','%s')", $task->getGuid(), base64_encode(serialize($task)));

        $this->getRepo()->query($sql);

    }


    /**
     *
     * @return Task
     */
    public function claimTask() {

        return unserialize(base64_decode($this->getRepo()->querySingle("SELECT task FROM tasks WHERE resolved = '0'")));

    }

    public function resolveTask(Task $task) {

        $sql = sprintf("UPDATE tasks SET task='%s',resolved=1 WHERE id='%s'", base64_encode(serialize($task)), $task->getGuid());

        $this->getRepo()->exec($sql);

    }

    public function removeTask($taskOrId) {

        $id = ($taskOrId instanceof Task) ? $id = $taskOrId->getGuid () : $taskOrId;

        $this->getRepo()->exec(sprintf("DELETE FROM tasks WHERE id = '%s'", $id));

    }

    public function isTaskResolved($taskOrId){

        $id = ($taskOrId instanceof Task) ? $id = $taskOrId->getGuid () : $taskOrId;

        $sql = sprintf("SELECT resolved FROM tasks WHERE id = '%s'", $id);

        return $this->getRepo()->querySingle($sql);

    }



    /**
     *
     * @return SQLite3
     */
    private function getRepo() {

        if (!$this->repo)
            return $this->initRepo();

        return $this->repo;
    }

    private function initRepo() {

        $this->repo = new \SQLite3('sqlite.db');
        $this->repo->query("

            CREATE TABLE IF NOT EXISTS tasks (
            id CHAR (32),
            resolved INTEGER default '0',
            task TEXT,
            PRIMARY KEY (id)
            );

            ");


        return $this->repo;
    }

}
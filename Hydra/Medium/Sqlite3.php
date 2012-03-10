<?php

namespace Hydra\Medium;

use Hydra\Task;

/**
 * Description of SqliteMedium
 *
 * @author Rutger de Knijf
 * @package Hydra
 */
class Sqlite3 extends Base {

    private $busyTimeOutMsec = 50;

    public function addTask(Task $task) {

        $this->getRepo()->busyTimeout($this->busyTimeOutMsec);

        $sql = sprintf("INSERT INTO tasks ( id, task ) VALUES ('%s','%s')", $task->getGuid(), base64_encode(serialize($task)));

        $this->getRepo()->query($sql);

    }

    public function resolveTask(Task $task) {

        $this->getRepo()->busyTimeout($this->busyTimeOutMsec);

        $sql = sprintf("UPDATE tasks SET task='%s',resolved=1 WHERE id='%s'", base64_encode(serialize($task)), $task->getGuid());

        $this->getRepo()->exec($sql);

    }

    public function removeTask($taskOrId) {

        $id = ($taskOrId instanceof Task) ? $id = $taskOrId->getGuid () : $taskOrId;

        $this->getRepo()->busyTimeout($this->busyTimeOutMsec);

        $this->getRepo()->exec(sprintf("DELETE FROM tasks WHERE id = '%s'", $id));

    }

    public function isTaskResolved($taskOrId){

        $this->getRepo()->busyTimeout($this->busyTimeOutMsec);

        $id = ($taskOrId instanceof Task) ? $id = $taskOrId->getGuid () : $taskOrId;

        return $this->getRepo()->querySingle("SELECT resolved FROM tasks WHERE id = '$id'");

    }

    protected function initRepo() {

        $this->repo = new \SQLite3(sys_get_temp_dir() . '/hydra.db');
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

    public function getTask($taskOrId) {

        $taskId = ($taskOrId instanceof Task) ? $taskId = $taskOrId->getGuid () : $taskOrId;

        $this->getRepo()->busyTimeout($this->busyTimeOutMsec);

        $row = $this->getRepo()->query("SELECT task FROM tasks WHERE id='" . $taskId . "'")->fetchArray(SQLITE3_ASSOC);;

        return unserialize(base64_decode($row['task']));


    }

    public function destroy() {

        $this->getRepo->close();

        //exec('rm ' . sys_get_temp_dir() . '/hydra.db' );

    }

}
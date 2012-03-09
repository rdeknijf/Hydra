<?php

namespace Hydra;

/**
 * Description of Medium
 *
 * @author Rutger de Knijf
 * @package Hydra
 */
interface Medium {

    public function addTask(Task $task);
    public function claimTask();
    public function resolveTask(Task $task);
    public function removeTask($taskOrId);
    public function isTaskResolved($taskOrId);

}
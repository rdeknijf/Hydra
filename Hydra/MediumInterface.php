<?php

namespace Hydra;

/**
 * Description of Medium
 *
 * @author Rutger de Knijf
 * @package Hydra
 */
interface MediumInterface {

    public function addTask(Task $task);
    public function resolveTask(Task $task);
    //public function removeTask($taskOrId);
    public function isTaskResolved($taskOrId);
    public function getTask($taskOrId);

}
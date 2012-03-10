<?php

namespace Hydra;

/**
 * Description of Logger
 *
 * @author Rutger de Knijf
 * @package Hydra
 */
class Logger {

    private $handle;

    public function __construct() {

        $this->handle = fopen('Hydra.log', 'a');

    }


    public function log($string) {

        fwrite($this->handle, "$string\n");


    }

    public function close() {

        fclose($this->handle);

    }

}
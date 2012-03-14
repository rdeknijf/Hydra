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

        $this->handle = fopen(sys_get_temp_dir() . '/Hydra.log', 'a');

    }


    public function log($string) {

        fwrite($this->handle, "$string\n");

        return $this;
    }

    public function close() {

        fclose($this->handle);

    }

}
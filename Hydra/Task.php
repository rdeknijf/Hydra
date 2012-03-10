<?php
namespace Hydra;

/**
 * Description of Task
 *
 * @author Rutger de Knijf
 * @package Hydra
 */
class Task {

    public $guid;

    public $script;

    public $args;

    public $output;

    public $resolved = false;

    public function __construct($script = null) {

        $this->guid = $this->generateGuid();

        $this->setScript($script);

    }

    public function setScript($script) {

        $this->script = $script;

    }

    public function getScript() {

        return $this->script;

    }



    public function getGuid() {

        return $this->guid;
    }


    private function generateGuid() {

        return md5(uniqid('', true));


    }

    public function getOutput() {

        return $this->output;

    }

    public function setOutput($output) {

        $this->output = $output;

    }

    public function setResolved($bool = true) {

        $this->resolved = $bool;

    }

}
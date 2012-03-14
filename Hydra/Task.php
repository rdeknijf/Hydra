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

    public $options;

    public $output;

    public $resolved = false;

    public function __construct($script = null, $options = null) {

        $this->guid = $this->generateGuid();

        $this->setScript($script);

        $this->setOptions($options);

    }

    /**
     * @param string $script Absolute path to script
     */
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

        return $this;

    }

    public function setResolved($bool = true) {

        $this->resolved = $bool;

        return $this;

    }

    /**
     * @param string $arg_key Argument key
     * @param string|null $arg_value Optional argument value
     */
    public function addOption($arg_key, $arg_value = null) {

        $this->options[$arg_key] = $arg_value;

        return $this;

    }

    public function setOptions($optionsArray) {

        $this->options = $optionsArray;

        return $this;

    }


    public function getOptions() {

        return $this->options;


    }

}
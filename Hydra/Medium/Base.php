<?php

namespace Hydra\Medium;

use Hydra\Task;

/**
 * Description of MediumBase
 *
 * @author Rutger de Knijf
 * @package Hydra
 */
abstract class Base implements MediumInterface {

    protected $repo;


    /**
     *
     * @return \Memcache
     */
    protected function getRepo() {

        if (!$this->repo) {

            $this->initRepo();

        }

        return $this->repo;

    }


}
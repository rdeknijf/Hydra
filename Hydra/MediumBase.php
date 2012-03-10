<?php

namespace Hydra;

/**
 * Description of MediumBase
 *
 * @author Rutger de Knijf
 * @package Hydra
 */
abstract class MediumBase implements MediumInterface {

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
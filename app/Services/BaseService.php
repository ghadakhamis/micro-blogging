<?php

namespace App\Services;


abstract class BaseService
{
    protected $repository;

    public function setRepository($repository)
    {
        $this->repository = $repository;
    }
}
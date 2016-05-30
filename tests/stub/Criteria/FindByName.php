<?php

namespace CodePress\CodeDatabase\Criteria;


use CodePress\CodeDataBase\Contracts\RepositoryInterface;

class FindByName implements \CodePress\CodeDatabase\Contracts\CriteriaInterface
{
    private $name;

    public function __construct($name)
    {
        $this->name = $name;
    }

    public function apply($model, RepositoryInterface $respository)
    {
        return $model->where('name', $this->name);
    }
}
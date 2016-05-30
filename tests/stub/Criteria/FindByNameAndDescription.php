<?php

namespace CodePress\CodeDatabase\Criteria;


use CodePress\CodeDataBase\Contracts\RepositoryInterface;

class FindByNameAndDescription implements \CodePress\CodeDatabase\Contracts\CriteriaInterface
{
    private $name;
    private $description;

    public function __construct($name, $description)
    {
        $this->name = $name;
        $this->description = $description;
    }

    public function apply($model, RepositoryInterface $respository)
    {
        return $model->where('name', $this->name)
                     ->where('description', $this->description);
    }
}
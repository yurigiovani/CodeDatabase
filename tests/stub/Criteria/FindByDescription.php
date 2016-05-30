<?php

namespace CodePress\CodeDatabase\Criteria;


use CodePress\CodeDataBase\Contracts\RepositoryInterface;

class FindByDescription implements \CodePress\CodeDatabase\Contracts\CriteriaInterface
{
    private $description;

    public function __construct($description)
    {
        $this->description = $description;
    }

    public function apply($model, RepositoryInterface $respository)
    {
        return $model->where('description', $this->description);
    }
}
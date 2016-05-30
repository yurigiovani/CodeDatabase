<?php

namespace CodePress\CodeDatabase\Criteria;


use CodePress\CodeDataBase\Contracts\RepositoryInterface;

class OrderDescByName implements \CodePress\CodeDatabase\Contracts\CriteriaInterface
{
    private $name;

    public function apply($model, RepositoryInterface $respository)
    {
        return $model->orderBy('name', 'desc');
    }
}
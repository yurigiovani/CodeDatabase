<?php

namespace CodePress\CodeDatabase\Criteria;


use CodePress\CodeDataBase\Contracts\RepositoryInterface;

class OrderDescById implements \CodePress\CodeDatabase\Contracts\CriteriaInterface
{
    public function apply($model, RepositoryInterface $respository)
    {
        return $model->orderBy('id', 'desc');
    }
}
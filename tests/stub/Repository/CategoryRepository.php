<?php

namespace CodePress\CodeDataBase\Repository;

use CodePress\CodeDataBase\Model\Category;
use CodePress\CodeDatabase\AbstractRepository;

class CategoryRepository extends AbstractRepository
{
    public function model()
    {
       return Category::class;
    }
}
<?php

namespace CodePress\CodeDataBase\Tests;

use CodePress\CodeDataBase\Contracts\CriteriaInterface;
use CodePress\CodeDatabase\Contracts\RepositoryInterface;
use CodePress\CodeDataBase\Model\Category;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\Query\Builder;
use Mockery as m;

class CriteriaInterfaceTestCase extends AbstractTestCase
{
    public function test_should_apply()
    {
        $categoryRepository = m::mock(RepositoryInterface::class);
        $categoryModel = m::mock(Category::class);
        $abstractCriteria = m::mock(CriteriaInterface::class);
        $queryBuilder = m::mock(Builder::class);

        $abstractCriteria->shouldReceive('apply')
                        ->with($categoryModel, $categoryRepository)
                        ->andReturn($queryBuilder);

        $this->assertInstanceOf(Builder::class, $abstractCriteria->apply($categoryModel, $categoryRepository));
    }
}
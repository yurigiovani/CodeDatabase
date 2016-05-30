<?php

namespace CodePress\CodeDataBase\Tests;

use CodePress\CodeDataBase\Contracts\CriteriaInterface;
use CodePress\CodeDatabase\Criteria\FindByNameAndDescription;
use CodePress\CodeDataBase\Repository\CategoryRepository;
use CodePress\CodeDataBase\Model\Category;
use CodePress\CodeDataBase\Tests\AbstractTestCase;
use Illuminate\Database\Eloquent\Builder;
use Mockery as m;

class FindByNameAndDescriptionTestCase extends AbstractTestCase
{
    /**
     * @var CategoryRepository
     */
    private $repository;
    private $criteria;

    public function setUp()
    {
        parent::setUp();
        $this->migrate();

        $this->repository = new CategoryRepository();
        $this->criteria = new FindByNameAndDescription('Category 1', 'Description 1');

        $this->createCategories();
    }

    public function test_if_instanceof_criteriainterface()
    {
        $this->assertInstanceOf(CriteriaInterface::class, $this->criteria);
    }

    public function test_if_apply_returns_querybuild()
    {
        $class = $this->repository->model();
        $result = $this->criteria->apply(new $class, $this->repository);

        $this->assertInstanceOf(Builder::class, $result);
    }

    public function test_if_apply_returns_data()
    {
        $class = $this->repository->model();
        $result = $this->criteria->apply(new $class, $this->repository)->get()->first();

        $this->assertEquals('Category 1', $result->name);
        $this->assertEquals('Description 1', $result->description);
    }

    private function createCategories()
    {
        Category::create([
            'name'  => 'Category 1',
            'description'   =>  'Description 1'
        ]);

        Category::create([
            'name'  => 'Category 2',
            'description'   =>  'Description 2'
        ]);

        Category::create([
            'name'  => 'Category 3',
            'description'   =>  'Description 3'
        ]);
    }
}
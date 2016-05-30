<?php

namespace CodePress\CodeDataBase\Tests;

use CodePress\CodeDatabase\Contracts\CriteriaInterface;
use CodePress\CodeDatabase\Criteria\FindByDescription;
use CodePress\CodeDatabase\Criteria\FindByName;
use CodePress\CodeDatabase\Criteria\FindByNameAndDescription;
use CodePress\CodeDatabase\Criteria\OrderDescByName;
use CodePress\CodeDatabase\Criteria\OrderDescById;
use CodePress\CodeDataBase\Repository\CategoryRepository;
use CodePress\CodeDataBase\Model\Category;
use CodePress\CodeDataBase\Tests\AbstractTestCase;
use CodePress\CodeDataBase\Contracts\CriteriaCollectionInterface;
use Illuminate\Database\Eloquent\Builder;
use Mockery as m;

class CategoryRepositoryCriteriaTest extends AbstractTestCase
{
    /**
     * @var CategoryRepository
     */
    private $repository;

    public function setUp()
    {
        parent::setUp();
        $this->migrate();

        $this->repository = new CategoryRepository();

        $this->createCategories();
    }

    public function test_if_instanceof_criteria_collection()
    {
        $this->assertInstanceOf(CriteriaCollectionInterface::class, $this->repository);
    }

    public function test_can_get_criteria_collection()
    {
        $result = $this->repository->getCriteriaCollection();
        $this->assertCount(0, $result);
    }

    public function test_if_can_add_criteria()
    {
        $mock = m::mock(CriteriaInterface::class);

        $result = $this->repository->addCriteria($mock);

        $this->assertInstanceOf(CategoryRepository::class, $result);
        $this->assertCount(1, $this->repository->getCriteriaCollection());
    }

    public function test_if_can_getbycriteria()
    {
        $criteria = new FindByNameAndDescription('Category 1', 'Description 1');
        $respository = $this->repository->getByCriteria($criteria);

        $this->assertInstanceOf(CategoryRepository::class, $respository);

        $result = $respository->all();
        $this->assertCount(1, $result);

        $result = $result->first();

        $this->assertEquals($result->name, 'Category 1');
        $this->assertEquals($result->description, 'Description 1');
    }

    public function test_if_can_applycriteria()
    {
        $this->createCategoryDescription();

        $criteria1 = new FindByDescription('Description');
        $criteria2 = new OrderDescByName();

        $this->repository->addCriteria($criteria1)
                         ->addCriteria($criteria2);
        $repository = $this->repository->applyCriteria();

        $this->assertInstanceOf(CategoryRepository::class, $repository);

        $result = $repository->all();
        $this->assertCount(3, $result);
        $this->assertEquals($result[0]->name, 'Category Um');
        $this->assertEquals($result[1]->name, 'Category Dois');
    }

    public function test_can_list_all_categories_with_criteria()
    {
        $this->createCategoryDescription();

        $criteria1 = new FindByDescription('Description');
        $criteria2 = new OrderDescByName();

        $this->repository->addCriteria($criteria1);
        $this->repository->addCriteria($criteria2);

        $result = $this->repository->all();
        $this->assertCount(3, $result);
        $this->assertEquals($result[0]->name, 'Category Um');
        $this->assertEquals($result[1]->name, 'Category Dois');
    }

    /**
     * @expectedException \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function test_can_find_category_with_criteria_and_exception()
    {
        $this->createCategoryDescription();

        $criteria1 = new FindByDescription('Description');
        $criteria2 = new FindByName('Category Dois');

        $this->repository->addCriteria($criteria1)
                         ->addCriteria($criteria2);

        $result = $this->repository->find(5);
    }

    public function test_can_find_category_with_criteria()
    {
        $this->createCategoryDescription();

        $criteria1 = new FindByDescription('Description');
        $criteria2 = new FindByName('Category Um');

        $this->repository->addCriteria($criteria1)
            ->addCriteria($criteria2);

        $result = $this->repository->find(5);

        $this->assertEquals($result->name, 'Category Um');
    }

    public function test_can_findby_category_with_criteria()
    {
        $this->createCategoryDescription();

        $criteria1 = new FindByName('Category Dois');
        $criteria2 = new OrderDescById();

        $this->repository->addCriteria($criteria1)
            ->addCriteria($criteria2);

        $result = $this->repository->findBy('description', 'Description');
        $this->assertCount(2, $result);
        $this->assertEquals($result[0]->id, 6);
        $this->assertEquals($result[0]->name, 'Category Dois');
        $this->assertEquals($result[1]->id, 4);
        $this->assertEquals($result[1]->name, 'Category Dois');
    }

    public function test_can_ignore_criteria()
    {
        $reflectionClass = new \ReflectionClass($this->repository);
        $reflectionProperty = $reflectionClass->getProperty('isIgnoreCriteria');
        $reflectionProperty->setAccessible(true);

        $result = $reflectionProperty->getValue($this->repository);
        $this->assertFalse($result);

        $this->repository->ignoreCriteria(true);

        $result = $reflectionProperty->getValue($this->repository);
        $this->assertTrue($result);

        $this->repository->ignoreCriteria(false);
        $result = $reflectionProperty->getValue($this->repository);
        $this->assertFalse($result);

        $this->repository->ignoreCriteria();
        $result = $reflectionProperty->getValue($this->repository);
        $this->assertTrue($result);

        $this->assertInstanceOf(CategoryRepository::class, $this->repository->ignoreCriteria(false));
    }

    public function test_if_can_ignorecriteria_with_applycriteria()
    {
        $this->createCategoryDescription();

        $criteria1 = new FindByDescription('Description');
        $criteria2 = new OrderDescByName();

        $this->repository->addCriteria($criteria1)
                         ->addCriteria($criteria2);

        $this->repository->ignoreCriteria();
        $this->repository->applyCriteria();

        $reflectionClass = new \ReflectionClass($this->repository);
        $reflectionProperty = $reflectionClass->getProperty('model');
        $reflectionProperty->setAccessible(true);
        $result = $reflectionProperty->getValue($this->repository);

        $this->assertInstanceOf(Category::class, $result);

        $this->repository->ignoreCriteria(false);

        $repository = $this->repository->applyCriteria();

        $this->assertInstanceOf(CategoryRepository::class, $repository);

        $result = $repository->all();
        $this->assertCount(3, $result);
        $this->assertEquals($result[0]->name, 'Category Um');
        $this->assertEquals($result[1]->name, 'Category Dois');
    }

    public function test_can_clearcriterias()
    {
        $this->createCategoryDescription();

        $criteria1 = new FindByName('Category Dois');
        $criteria2 = new OrderDescById();

        $this->repository->addCriteria($criteria1)
            ->addCriteria($criteria2);

        $this->assertInstanceOf(CategoryRepository::class, $this->repository->clearCriteria());

        $result = $this->repository->findBy('description', 'Description');
        $this->assertCount(3, $result);

        $reflectionClass = new \ReflectionClass($this->repository);
        $reflectionProperty = $reflectionClass->getProperty('model');
        $reflectionProperty->setAccessible(true);

        $result = $reflectionProperty->getValue($this->repository);

        $this->assertInstanceOf(Category::class, $result);
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

    private function createCategoryDescription()
    {
        Category::create([
            'name'  => 'Category Dois',
            'description'   =>  'Description'
        ]);

        Category::create([
            'name'  => 'Category Um',
            'description'   =>  'Description'
        ]);

        Category::create([
            'name'  => 'Category Dois',
            'description'   =>  'Description'
        ]);
    }
}
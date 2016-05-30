<?php

namespace CodePress\CodeDataBase\Tests;

use CodePress\CodeDataBase\AbstractRepository;
use CodePress\CodeDatabase\Contracts\RepositoryInterface;
use CodePress\CodeDataBase\Model\Category;
use CodePress\CodeDataBase\Tests\AbstractTestCase;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Mockery as m;

class AbstractRepositoryTestCase extends AbstractTestCase
{
    public function setUp()
    {
        parent::setUp();
        $this->migrate();

        Category::create([  'name'    =>  'Category',
                            'description'   => 'Description category'
                        ]);

        echo Category::all()->first()->name;
    }

    public function test_if_implements_repositoryinterface()
    {
        $abstractRepository = m::mock(AbstractRepository::class);

        $this->assertInstanceOf(AbstractRepository::class, $abstractRepository);
    }

    public function test_should_return_all_without_arguments()
    {
        $repository = m::mock(AbstractRepository::class);
        $mockStd = m::mock(\stdClass::class);
        $mockStd->id = 1;
        $mockStd->name = 'name';
        $mockStd->descriptio = 'description';

        $repository->shouldReceive('all')->andReturn([$mockStd, $mockStd, $mockStd]);

        $result = $repository->all();

        $this->assertCount(3, $result);
        $this->assertInstanceOf(\stdClass::class, $result[0]);
    }

    public function test_should_return_all_with_arguments()
    {
        $repository = m::mock(AbstractRepository::class);
        $mockStd = m::mock(\stdClass::class);
        $mockStd->id = 1;
        $mockStd->name = 'name';
        $mockStd->descriptio = 'description';

        $repository->shouldReceive('all')->with(['id', 'name'])->andReturn([$mockStd, $mockStd, $mockStd]);

        $this->assertCount(3, $repository->all(['id', 'name']));
        $this->assertInstanceOf(\stdClass::class, $repository->all(['id', 'name'])[0]);
    }

    public function test_should_return_create()
    {
        $repository = m::mock(AbstractRepository::class);
        $mockStd = m::mock(\stdClass::class);
        $mockStd->id = 1;
        $mockStd->name = 'stdClassName';

        $repository->shouldReceive('create')
                   ->with(['name' => 'stdClassName'])
                   ->andReturn($mockStd);

        $result = $repository->create(['name' => 'stdClassName']);

        $this->assertEquals(1, $result->id);
        $this->assertInstanceOf(\stdClass::class, $result);
    }

    public function test_should_return_update_success()
    {
        $repository = m::mock(AbstractRepository::class);
        $mockStd = m::mock(\stdClass::class);
        $mockStd->id = 1;
        $mockStd->name = 'stdClassName';

        $repository->shouldReceive('update')
            ->with(['name' => 'stdClassName'], 1)
            ->andReturn($mockStd);

        $result = $repository->update(['name' => 'stdClassName'], 1);

        $this->assertEquals(1, $result->id);
        $this->assertInstanceOf(\stdClass::class, $result);
    }

    /**
     * @expectedException \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function test_should_return_update_fail()
    {
        $repository = m::mock(AbstractRepository::class);
        $modelException = new ModelNotFoundException();
        $modelException->setModel(\sdtClass::class);

        $repository->shouldReceive('update')
            ->with(['name' => 'stdClassName'], 0)
            ->andThrow($modelException);

        $repository->update(['name' => 'stdClassName'], 0);
    }

    public function test_should_return_delete_success()
    {
        $repository = m::mock(AbstractRepository::class);

        $repository->shouldReceive('delete')
            ->with(1)
            ->andReturn(true);

        $result = $repository->delete(1);

        $this->assertEquals(true, $result);
    }

    /**
     * @expectedException \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function test_should_return_delete_fail()
    {
        $repository = m::mock(AbstractRepository::class);

        $modelException = new ModelNotFoundException();
        $modelException->setModel(\sdtClass::class);

        $repository->shouldReceive('delete')
            ->with(0)
            ->andThrow($modelException);

        $repository->delete(0);
    }

    public function test_should_return_find_without_columns_success()
    {
        $repository = m::mock(AbstractRepository::class);

        $mockStd = m::mock(\stdClass::class);
        $mockStd->id = 1;
        $mockStd->name = 'stdClassName';
        $mockStd->description = "Description";

        $repository->shouldReceive('find')
            ->with(1)
            ->andReturn($mockStd);

        $this->assertInstanceOf(\stdClass::class, $repository->find(1));
    }

    public function test_should_return_find_with_columns_success()
    {
        $repository = m::mock(AbstractRepository::class);

        $mockStd = m::mock(\stdClass::class);
        $mockStd->id = 1;
        $mockStd->name = 'stdClassName';

        $repository->shouldReceive('find')
            ->with(1, ['id', 'name'])
            ->andReturn($mockStd);

        $this->assertInstanceOf(\stdClass::class, $repository->find(1, ['id', 'name']));
    }

    /**
     * @expectedException \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function test_should_return_find_fail()
    {
        $repository = m::mock(AbstractRepository::class);

        $modelException = new ModelNotFoundException();
        $modelException->setModel(\sdtClass::class);

        $repository->shouldReceive('find')
            ->with(0)
            ->andThrow($modelException);

        $repository->find(0);
    }

    public function test_should_return_findBy_with_columns_success()
    {
        $repository = m::mock(AbstractRepository::class);

        $mockStd = m::mock(\stdClass::class);
        $mockStd->id = 1;
        $mockStd->name = 'stdClassName';
        $mockStd->description = "Description";

        $repository->shouldReceive('findBy')
            ->with('name', 'my-data', ['id', 'name'])
            ->andReturn([$mockStd, $mockStd, $mockStd]);

        $result = $repository->findBy('name', 'my-data', ['id', 'name']);

        $this->assertCount(3, $result);
        $this->assertInstanceOf(\stdClass::class, $result[0]);
    }

    public function test_should_return_findBy_empty_success()
    {
        $repository = m::mock(AbstractRepository::class);

        $repository->shouldReceive('findBy')
            ->with('name', 'my-data', ['id', 'name'])
            ->andReturn([]);

        $result = $repository->findBy('name', 'my-data', ['id', 'name']);

        $this->assertCount(0, $result);
    }

    /**
     * @expectedException \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function test_should_return_findBy_fail()
    {
        $repository = m::mock(AbstractRepository::class);

        $modelException = new ModelNotFoundException();
        $modelException->setModel(\stdClass::class);

        $repository->shouldReceive('findBy')
            ->with('name', 'my-data', ['id', 'name'])
            ->andThrow($modelException);

        $repository->findBy('name', 'my-data', ['id', 'name']);
    }
}
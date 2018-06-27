<?php

namespace Tests;

use Tests\TestCase;
use PhpDao\QueryBuilder;
use Tests\UserModelTest;

class ModelTest extends TestCase
{
    private $queryBuilder = null;
    
    private $userModel = null;

    public function setUp()
    {
        if (is_null($this->queryBuilder)) {
            QueryBuilder::setConnection($this->connection);
            $this->queryBuilder = new QueryBuilder();
        }

        if (is_null($this->userModel)) {
            $this->userModel = new UserModelTest();
        }

        parent::setUp();
    }

    public function tearDown()
    {
        $this->pdo->query("DELETE FROM users");
        parent::tearDown();
    }

    public function testShouldInsertData()
    {
        $userCreated = $this->userModel->create([
            'name' => 'user name'
        ]);
        $this->assertNotEmpty($userCreated);
    }

    public function testShouldSelectAllData()
    {
        $this->userModel->create(['name' => 'user name']);
        $users = $this->userModel->all();
        $this->assertNotEmpty($users);
        $this->assertEquals(1, count($users));
    }

    public function testShouldSelectOnlyOneDataById()
    {
        $userCreated = $this->userModel->create(
            ['name' => 'user name']
        );
        $user = $this->userModel->find($userCreated->id);
        $this->assertEquals($userCreated->id, $user->id);
        $this->assertEquals('user name', $user->name);
    }

    public function testShouldUpdateData()
    {
        $userCreated = $this->userModel->create(
            ['name' => 'user name']
        );
        $this->userModel->save([
            'name' => 'updated',
            'id' => $userCreated->id
        ]);
        $user = $this->userModel->find($userCreated->id);
        $this->assertEquals('updated', $user->name);
    }

    public function testShouldRemoveData()
    {
        $userCreated = $this->userModel->create(
            ['name' => 'user name']
        );
        $this->userModel->remove($userCreated->id);
        $user = $this->userModel->find($userCreated->id);
        $this->assertNull($user);
    }
}

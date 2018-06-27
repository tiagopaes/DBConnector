<?php

namespace Tests;

use Tests\TestCase;
use PhpDao\QueryBuilder;

class QueryBuilderTest extends TestCase
{
    private $queryBuilder = null;
    
    private $table = 'users';

    public function setUp()
    {
        if (is_null($this->queryBuilder)) {
            QueryBuilder::setConnection($this->connection);
            $this->queryBuilder = new QueryBuilder();
        }

        parent::setUp();
    }

    public function tearDown()
    {
        $this->pdo->query("DELETE FROM {$this->table}");
        parent::tearDown();
    }

    public function testShouldInsertData()
    {
        $insertedId = $this->queryBuilder->table($this->table)
            ->fields(['name'])
            ->insert(['Reimu']);

        $this->assertTrue(is_int($insertedId));
    }

    public function testShouldSelectData()
    {
        $this->queryBuilder->table($this->table)
            ->fields(['name'])
            ->insert(['Reimu']);

        $result = $this->queryBuilder->table($this->table)
                ->select();

        $this->assertNotEmpty($result);
        $this->assertTrue(is_array($result));
        $this->assertEquals(1, count($result));
    }

    public function testShouldSelectWhereData()
    {
        $insertId = $this->queryBuilder->table($this->table)
            ->fields(['name'])
            ->insert(['Reimu']);

        $result = $this->queryBuilder->table($this->table)
            ->where('id = ?')
            ->select([$insertId]);

        $this->assertNotEmpty($result);
        $this->assertTrue(is_array($result));
        $this->assertEquals(1, count($result));
    }

    public function testShouldUpdateData()
    {
        $insertId = $this->queryBuilder->table($this->table)
            ->fields(['name'])
            ->insert(['Reimu']);

        $result = $this->queryBuilder->table($this->table)
            ->fields(['name'])
            ->where('id = ?')
            ->update(['Reisen'], [$insertId]);

        $user = $this->queryBuilder->table($this->table)
            ->where('id = ?')
            ->field('name')
            ->select([$insertId]);

        $this->assertEquals(1, $result);
        $this->assertEquals('Reisen', $user[0]->name);
    }

    public function testShouldDeleteData()
    {
        $insertId = $this->queryBuilder->table($this->table)
            ->fields(['name'])
            ->insert(['Reimu']);

        $result = $this->queryBuilder->table($this->table)
            ->where(['id = ?'])
            ->delete([$insertId]);

        $user = $this->queryBuilder->table($this->table)
            ->where('id = ?')
            ->select([$insertId]);
        
        $this->assertEquals(1, $result);
        $this->assertEmpty($user);
    }

    public function testShouldToDoCrudOperationsByQueryMethod()
    {
        //test insert
        $insertedId = $this->queryBuilder->query(
            "INSERT INTO {$this->table} SET name = 'test'"
        );
        $this->assertTrue(is_numeric($insertedId));

        //test select
        $users = $this->queryBuilder->query(
            "SELECT * FROM {$this->table}"
        );
        $this->assertNotEmpty($users);

        //test update
        $this->queryBuilder->query(
            "UPDATE {$this->table} SET name = 'updated' WHERE id = {$users[0]->id}"
        );
        $users = $this->queryBuilder->query(
            "SELECT * FROM {$this->table}"
        );
        $this->assertEquals('updated', $users[0]->name);

        //test delete
        $this->queryBuilder->query(
            "DELETE FROM {$this->table}"
        );
        $users = $this->queryBuilder->query(
            "SELECT * FROM {$this->table}"
        );
        $this->assertEmpty($users);
    }
}

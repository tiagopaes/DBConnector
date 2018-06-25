<?php

namespace Tests;

use Tests\TestCase;
use PhpDao\QueryBuilder;

class QueryBuilderTest extends TestCase
{
    private $queryBuilder = null;

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
        $this->pdo->query("DELETE FROM users");
        parent::tearDown();
    }

    public function testShouldInsertData()
    {
        $insertedId = $this->queryBuilder->table('users')
            ->fields(['name_user'])
            ->insert(['Reimu']);

        $this->assertTrue(is_int($insertedId));
    }

    public function testShouldSelectData()
    {
        $this->queryBuilder->table('users')
            ->fields(['name_user'])
            ->insert(['Reimu']);

        $result = $this->queryBuilder->table('users')
                ->select();

        $this->assertNotEmpty($result);
        $this->assertTrue(is_array($result));
        $this->assertEquals(1, count($result));
    }

    public function testShouldSelectWhereData()
    {
        $insertId = $this->queryBuilder->table('users')
            ->fields(['name_user'])
            ->insert(['Reimu']);

        $result = $this->queryBuilder->table('users')
            ->where('id_user = ?')
            ->select([$insertId]);

        $this->assertNotEmpty($result);
        $this->assertTrue(is_array($result));
        $this->assertEquals(1, count($result));
    }

    public function testShouldUpdateData()
    {
        $insertId = $this->queryBuilder->table('users')
            ->fields(['name_user'])
            ->insert(['Reimu']);

        $result = $this->queryBuilder->table('users')
            ->fields(['name_user'])
            ->where('id_user = ?')
            ->update(['Reisen'], [$insertId]);

        $user = $this->queryBuilder->table('users')
            ->where('id_user = ?')
            ->field('name_user')
            ->select([$insertId]);

        $this->assertEquals(1, $result);
        $this->assertEquals('Reisen', $user[0]->name_user);
    }

    public function testShouldDeleteData()
    {
        $insertId = $this->queryBuilder->table('users')
            ->fields(['name_user'])
            ->insert(['Reimu']);

        $result = $this->queryBuilder->table('users')
            ->where(['id_user = ?'])
            ->delete([$insertId]);

        $this->assertEquals(1, $result);
    }
}

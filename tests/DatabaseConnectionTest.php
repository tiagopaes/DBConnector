<?php

namespace PhpDao;

use PHPUnit\Framework\TestCase;

class DatabaseConnectionTest extends TestCase
{
    public function testShouldGetTheHostNameSetted()
    {
        $database = new DatabaseConnectionConcretClass();
        $this->assertEquals('localhost', $database->host());
    }

    public function testShouldGetTheDBNameSetted()
    {
        $database = new DatabaseConnectionConcretClass();
        $this->assertEquals('dbName', $database->name());
    }

    public function testShouldGetTheDBUserSetted()
    {
        $database = new DatabaseConnectionConcretClass();
        $this->assertEquals('dbUser', $database->user());
    }

    public function testShouldGetTheDBPasswordSetted()
    {
        $database = new DatabaseConnectionConcretClass();
        $this->assertEquals('', $database->password());
    }

    public function testShouldGetTheDBDriverSetted()
    {
        $database = new DatabaseConnectionConcretClass();
        $this->assertEquals('mysql', $database->driver());
    }
}

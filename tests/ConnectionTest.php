<?php

namespace PhpDao;

use PHPUnit\Framework\TestCase;

class ConnectionTest extends TestCase
{
    public function testShouldGetTheHostNameSetted()
    {
        $database = new ConnectionConcretClass();
        $this->assertEquals('localhost', $database->host());
    }

    public function testShouldGetTheDBNameSetted()
    {
        $database = new ConnectionConcretClass();
        $this->assertEquals('dbName', $database->name());
    }

    public function testShouldGetTheDBUserSetted()
    {
        $database = new ConnectionConcretClass();
        $this->assertEquals('dbUser', $database->user());
    }

    public function testShouldGetTheDBPasswordSetted()
    {
        $database = new ConnectionConcretClass();
        $this->assertEquals('', $database->password());
    }

    public function testShouldGetTheDBDriverSetted()
    {
        $database = new ConnectionConcretClass();
        $this->assertEquals('mysql', $database->driver());
    }
}

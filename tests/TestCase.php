<?php

namespace Tests;

use PHPUnit\Framework\TestCase as PHPUnitTestCase;
use Dotenv\Dotenv;
use PDO;
use PhpDao\Connection;

class TestCase extends PHPUnitTestCase
{
    /**
     * This attribute is an instance of PhpDao\Connection
     */
    protected $connection;

    /**
     * This attribute is an instance of PDO
     */
    protected $pdo;

    public function __construct()
    {
        $dotenv = new Dotenv(__DIR__ . '/../');
        $dotenv->load();

        $this->pdo = new PDO(
            "{$_ENV['PHPDAO_DB_DRIVER']}:host={$_ENV['PHPDAO_DB_HOST']};
            port={$_ENV['PHPDAO_DB_PORT']};
            dbname={$_ENV['PHPDAO_DB_DATABASE']}",
            "{$_ENV['PHPDAO_DB_USER']}",
            "{$_ENV['PHPDAO_DB_PASSWORD']}"
        );

        $this->pdo->setAttribute(
            PDO::ATTR_ERRMODE,
            PDO::ERRMODE_EXCEPTION
        );

        $this->connection = new Connection($this->pdo);
        parent::__construct();
    }
}

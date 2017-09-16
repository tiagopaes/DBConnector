<?php

namespace PhpDao;

use PDO;
use PDOStatement;
use Exception;

class Connection
{
    private $pdo = null;

    private $options = [];
    
    public function __construct(array $options)
    {
        $this->options = $options;
    }

    public function connect()
    {
        if (!$this->pdo) {
            $this->pdo = new PDO(
                $this->dsn(),
                $this->options['user'],
                $this->options['password']
            );
        }

        return $this->pdo;
    }

    public function dsn()
    {
        $host = "host={$this->options['host']}";
        $port = "port={$this->options['port']}";
        $dbname = "dbname={$this->options['database']}";
        $driver = $this->options['driver'];
        
        return "$driver:{$host};{$port};{$dbname}";
    }

    public final function statement($sql)
    {
        return $this->connect()->prepare($sql);
    }

    public final function executeInsert($sql, array $values)
    {
        $statement = $this->statement($sql);
        if ($statement && $statement->execute(array_values($values))) {
            return $this->connect()->lastInsertId();
        }
        return null;
    }

    public final function executeSelect($sql, array $values)
    {
        $statement = $this->statement($sql);
        if ($statement && $statement->execute(array_values($values))) {
            return $statement->fetchAll(PDO::FETCH_ASSOC);
        }
        return null;
    }

    public final function executeUpdate($sql, array $values)
    {
        return $this->execute($sql, $values);
    }

    public final function executeDelete($sql, array $values)
    {
        return $this->execute($sql, $values);
    }

    public final function execute($sql, array $values)
    {
        $statement = $this->statement($sql);
        if ($statement && $statement->execute(array_values($values))) {
            return $statement->rowCount();
        }
        return null;
    }
    
    public function getOptions()
    {
        return $this->options;
    }
}
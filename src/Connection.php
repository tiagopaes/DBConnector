<?php

namespace PhpDao;

use PDO;
use PDOStatement;
use Exception;

/**
 * Database Connection class.
 * @package PhpDao
 */
class Connection
{
    /**
     * @var PDO
     */
    private $pdo = null;

    /**
     * Database config data options.
     * @var array
     */
    private $options = [];

    /**
     * Connection constructor.
     * 
     * @param array $options
     * 
     * @throws Exception
     */
    public function __construct(array $options)
    {
        $this->options = $options;
    }

    /**
     * Returns the Database config data options.
     * 
     * @return array
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * Retrieves the PDO object.
     * 
     * @return PDO
     */
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

    /**
     * Returns the PDO dsn string.
     * 
     * @return string
     */
    public function dsn()
    {
        $host = "host={$this->options['host']}";
        $port = "port={$this->options['port']}";
        $dbname = "dbname={$this->options['database']}";
        $driver = $this->options['driver'];
        
        return "$driver:{$host};{$port};{$dbname}";
    }

    /**
     * Returns a PDOStatement object.
     * 
     * @param string $query A sql query
     * 
     * @return PDOStatement
     */
    public final function statement(string $query)
    {
        return $this->connect()->prepare($query);
    }

    /**
     * Executes a sql select query.
     * 
     * @param string $query A sql query
     * 
     * @param array $values A array of values to execute
     * the query.
     * 
     * @param string $className The name of that will be
     * returned with the recorded values.
     * 
     * @return array Result of given sql query.
     * 
     * @throws Exception
     */
    public final function executeSelect(
        string $query,
        array $values,
        string $className = 'stdClass'
    ) {
        $statement = $this->statement($query);
        $statement->execute(array_values($values));
        return $statement->fetchAll(
            PDO::FETCH_CLASS,
            $className
        );
    }

    /**
     * Executes a sql insert query.
     * 
     * @param string $query A sql query
     * 
     * @param array $values A array of values to execute
     * the query.
     * 
     * @return int Result of last inserted id if exists.
     * 
     * @throws Exception
     */
    public final function executeInsert(
        string $query,
        array $values
    ) {
        $statement = $this->statement($query);
        $statement->execute(array_values($values));
        return $this->connect()->lastInsertId();
    }

    /**
     * Executes a sql update query.
     * 
     * @param string $query A sql query
     * 
     * @param array $values A array of values to execute
     * the query.
     * 
     * @return int Result of executed query.
     * 
     * @throws Exception
     */
    public final function executeUpdate(
        string $query,
        array $values
    ) {
        return $this->execute($query, $values);
    }

    /**
     * Executes a sql delete query.
     * 
     * @param string $query A sql query
     * 
     * @param array $values A array of values to execute
     * the query.
     * 
     * @return int Result of executed query.
     * 
     * @throws Exception
     */
    public final function executeDelete(
        string $query,
        array $values
    ) {
        return $this->execute($query, $values);
    }

    /**
     * Executes a sql query.
     * 
     * @param string $query A sql query
     * 
     * @param array $values A array of values to execute
     * the query.
     * 
     * @return int Result of executed query.
     * 
     * @throws Exception
     */
    public final function execute($sql, array $values)
    {
        $statement = $this->statement($sql);
        $statement->execute(array_values($values));
        return $statement->rowCount();
    }
}

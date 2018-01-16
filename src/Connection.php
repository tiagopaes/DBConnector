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
     * The PDO Object.
     * @var PDO
     */
    private $pdo = null;

    /**
     * Connection constructor.
     * 
     * @param PDO $pdo The PDO object
     * 
     * @throws Exception
     */
    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * Retrieves the PDO object.
     * 
     * @return PDO
     */
    public function connect()
    {
        return $this->pdo;
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

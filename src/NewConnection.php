<?php

namespace PhpDao;

use PDO;
use PDOStatement;
use Exception;

/**
 * Class Connection
 * @package Hero
 */

class NewConnection
{
    /**
     * @var PDO
     */
    private $pdo = null;
    /**
     * @var array
     */
    private $options = [];
    /**
     * Connection constructor.
     * @param array $options
     * @throws Exception
     */
    public function __construct(array $options)
    {
        $this->options = $options;
    }
    /**
     * @return PDO
     */
    public function connect()
    {
        if (!$this->pdo) {
            $this->pdo = new PDO($this->dsn(), $this->options['user'], $this->options['password']);
        }
        return $this->pdo;
    }
    /**
     * @return string
     */
    public function dsn()
    {
        return "mysql:host={$this->options['host']};port={$this->options['port']};dbname={$this->options['database']}";
    }
    /**
     * @param $sql
     * @return PDOStatement
     */
    public final function statement($sql)
    {
        return $this->connect()->prepare($sql);
    }
    /**
     * @param string $sql
     * @param array $values
     * @return string
     */
    public final function executeInsert($sql, array $values)
    {
        $statement = $this->statement($sql);
        if ($statement && $statement->execute(array_values($values))) {
            return $this->connect()->lastInsertId();
        }
        return null;
    }
    /**
     * @param string $sql
     * @param array $values
     * @return array
     */
    public final function executeSelect($sql, array $values)
    {
        $statement = $this->statement($sql);
        if ($statement && $statement->execute(array_values($values))) {
            return $statement->fetchAll(PDO::FETCH_OBJ);
        }
        return null;
    }
    /**
     * @param string $sql
     * @param array $values
     * @return int
     */
    public final function executeUpdate($sql, array $values)
    {
        return $this->execute($sql, $values);
    }
    /**
     * @param string $sql
     * @param array $values
     * @return int
     */
    public final function executeDelete($sql, array $values)
    {
        return $this->execute($sql, $values);
    }
    /**
     * @param $sql
     * @param array $values
     * @return int|null
     */
    public final function execute($sql, array $values)
    {
        $statement = $this->statement($sql);
        if ($statement && $statement->execute(array_values($values))) {
            return $statement->rowCount();
        }
        return null;
    }
    /**
     * @return array
     */
    public function getOptions()
    {
        return $this->options;
    }
}
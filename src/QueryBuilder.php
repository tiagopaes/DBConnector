<?php

namespace PhpDao;

use PhpDao\Connection;

/**
 * Class QueryBuilder
 * @package PhpDao
 * @method QueryBuilder table (string $table)
 * @method QueryBuilder join (string $join)
 * @method QueryBuilder fields (array $fields)
 * @method QueryBuilder where (array $where)
 * @method QueryBuilder order (array $order)
 * @method QueryBuilder group (array $group)
 * @method QueryBuilder having (array $having)
 * @method QueryBuilder limit (array $join)
 */
class QueryBuilder
{
    /**
     * The database connection object.
     *
     * @var Connection
     */
    protected static $connection;

    /**
     * The sql clausules to build the query.
     *
     * @var array
     */
    protected $clausules = [];

    /**
     * Implements magic method to fill the
     * $clausules property.
     *
     * @param string $name
     *
     * @param array $arguments
     *
     * @return object $this
     */
    public function __call(string $name, array $arguments)
    {
        $clausule = $arguments[0];
        if (count($arguments) > 1) {
            $clausule = $arguments;
        }
        $this->clausules[strtolower($name)] = $clausule;
        return $this;
    }

    /**
     * Sets the property $connection.
     *
     * @param  Connection $connection
     *
     * @return void
     */
    public static function setConnection(Connection $connection)
    {
        self::$connection = $connection;
    }

    /**
     * Returns the property $connection.
     *
     * @return Connection
     */
    public function getConnection()
    {
        return self::$connection;
    }

    /**
     * Returns the model class name.
     *
     * @return string
     */
    protected function getClassName()
    {
        $className = get_class($this);
        if ($className == self::class) {
            $className = 'stdClass';
        }
        return $className;
    }

    /**
     * Builds a select query and returns the
     * its result.
     *
     * @param array $values The values to build the query.
     *
     * @return array Returns the result of executed query.
     */
    public function select(array $values = [])
    {
        $table = isset($this->clausules['table']) ? $this->clausules['table'] : '<table>';
        
        $_fields = isset($this->clausules['fields']) ? $this->clausules['fields'] : ['*'];
        $fields = implode(', ', $_fields);
        $join = isset($this->clausules['join']) ? $this->clausules['join'] : '';

        $command = [];
        $command[] = 'SELECT';
        $command[] = $fields;
        $command[] = 'FROM';
        $command[] = $table;
        
        if ($join) {
            $command[] = $join;
        }
        
        $clausules = [
            'where' => [
                'instruction' => 'WHERE',
                'separator' => ' ',
            ],
            'group' => [
                'instruction' => 'GROUP BY',
                'separator' => ', ',
            ],
            'order' => [
                'instruction' => 'ORDER BY',
                'separator' => ', ',
            ],
            'having' => [
                'instruction' => 'HAVING',
                'separator' => ' AND ',
            ],
            'limit' => [
                'instruction' => 'LIMIT',
                'separator' => ',',
            ],
        ];
        foreach ($clausules as $key => $clausule) {
            if (isset($this->clausules[$key])) {
                $value = $this->clausules[$key];
                if (is_array($value)) {
                    $value = implode($clausule['separator'], $this->clausules[$key]);
                }
                $command[] = $clausule['instruction'] . ' ' . $value;
            }
        }
        
        $sql = implode(' ', $command);
        return $this->getConnection()->executeSelect($sql, $values, $this->getClassName());
    }

    /**
     * Builds a insert query and returns the
     * its result.
     *
     * @param array $values The values to build the query.
     *
     * @return array Returns the result of executed query.
     */
    public function insert(array $values)
    {
        $table = isset($this->clausules['table']) ? $this->clausules['table'] : '<table>';
        $_fields = isset($this->clausules['fields']) ? $this->clausules['fields'] : '<fields>';
        $fields = implode(', ', $_fields);
        $_placeholders = array_map(function () {
            return '?';
        }, $_fields);
        $placeholders = implode(', ', $_placeholders);
        
        $command = [];
        $command[] = 'INSERT INTO';
        $command[] = $table;
        $command[] = '(' . $fields . ')';
        $command[] = 'VALUES';
        $command[] = '(' . $placeholders . ')';
        
        $sql = implode(' ', $command);
        
        return (int) $this->getConnection()->executeInsert($sql, $values);
    }

    /**
     * Builds a update query and returns the
     * its result.
     *
     * @param array $values The values to be recorded.
     *
     * @param array $filters The values to build the query.
     *
     * @return array Returns the result of executed query.
     */
    public function update(array $values, array $filters = [])
    {
        $table = isset($this->clausules['table']) ? $this->clausules['table'] : '<table>';
        $join = isset($this->clausules['join']) ? $this->clausules['join'] : '';
     
        $_fields = isset($this->clausules['fields']) ? $this->clausules['fields'] : '<fields>';
        $sets = $_fields;
     
        if (is_array($_fields)) {
            $sets = implode(', ', array_map(function ($value) {
                return $value . ' = ?';
            }, $_fields));
        }
     
        $command = [];
        $command[] = 'UPDATE';
        $command[] = $table;
        if ($join) {
            $command[] = $join;
        }
        $command[] = 'SET';
        $command[] = $sets;
     
        $clausules = [
            'where' => [
                'instruction' => 'WHERE',
                'separator' => ' ',
            ]
        ];
     
        foreach ($clausules as $key => $clausule) {
            if (isset($this->clausules[$key])) {
                $value = $this->clausules[$key];
                if (is_array($value)) {
                    $value = implode($clausule['separator'], $this->clausules[$key]);
                }
                $command[] = $clausule['instruction'] . ' ' . $value;
            }
        }
     
        $sql = implode(' ', $command);
     
        return $this->getConnection()->executeUpdate($sql, array_merge($values, $filters));
    }

    /**
     * Builds a delete query and returns the
     * its result.
     *
     * @param array $filters The values to build the query.
     *
     * @return array Returns the result of executed query.
     */
    public function delete(array $filters)
    {
        $table = isset($this->clausules['table']) ? $this->clausules['table'] : '<table>';
        $join = isset($this->clausules['join']) ? $this->clausules['join'] : '';
        
        $command = [];
        $command[] = 'DELETE FROM';
        $command[] = $table;
        if ($join) {
            $command[] = $join;
        }
        $clausules = [
            'where' => [
                'instruction' => 'WHERE',
                'separator' => ' ',
            ]
        ];
        
        foreach ($clausules as $key => $clausule) {
            if (isset($this->clausules[$key])) {
                $value = $this->clausules[$key];
                if (is_array($value)) {
                    $value = implode($clausule['separator'], $this->clausules[$key]);
                }
                $command[] = $clausule['instruction'] . ' ' . $value;
            }
        }
        
        $sql = implode(' ', $command);
        
        return $this->getConnection()->executeDelete($sql, $filters);
    }

    /**
     * Executes a sql query and returns the result.
     *
     * @param string $query The sql query to be executed.
     *
     * @return mixed Returns the result of executed query.
     */
    public function query(string $query)
    {
        $command = ucfirst(
            strtolower(explode(' ', trim($query))[0])
        );
        $command = 'execute' . $command;
        
        return $this->getConnection()
            ->$command($query, [], $this->getClassName());
    }
}

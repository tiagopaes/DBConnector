<?php

namespace PhpDao;

class QueryBuilder
{
    private $connection;

    private $clausules = [];
    
    function __call($name, $arguments)
    {
        $clausule = $arguments[0];
        if (count($arguments) > 1) {
            $clausule = $arguments;
        }
        $this->clausules[strtolower($name)] = $clausule;
        return $this;
    }

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function insert($values)
    {
        $table = isset($this->clausules['table']) ? $this->clausules['table'] : '<table>';
        $_fields = isset($this->clausules['fields']) ? $this->clausules['fields'] : '<fields>';
        $fields = implode(', ', $_fields);
        $_placeholders = array_map(function() {
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
        
        return $this->connection->executeInsert($sql, $values);
    }

    public function select($values = [])
    {
        $table = isset($this->clausules['table']) ? $this->clausules['table'] : '<table>';
        
        $_fields = isset($this->clausules['fields']) ? $this->clausules['fields'] : '*';
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
        foreach($clausules as $key => $clausule) {
            if (isset($this->clausules[$key])) {
                $value = $this->clausules[$key];
                if (is_array($value)) {
                    $value = implode($clausule['separator'], $this->clausules[$key]);
                }
                $command[] = $clausule['instruction'] . ' ' . $value;
            }
        }
        
        $sql = implode(' ', $command);
        
        return $this->connection->executeSelect($sql, $values);
    }

    public function update($values, $filters = [])
    {
        $table = isset($this->clausules['table']) ? $this->clausules['table'] : '<table>';
        $join = isset($this->clausules['join']) ? $this->clausules['join'] : '';
     
        $_fields = isset($this->clausules['fields']) ? $this->clausules['fields'] : '<fields>';
        $sets = $_fields;
     
        if (is_array($_fields)) {
            $sets = implode(', ', array_map(function($value) {
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
     
        foreach($clausules as $key => $clausule) {
            if (isset($this->clausules[$key])) {
                $value = $this->clausules[$key];
                if (is_array($value)) {
                    $value = implode($clausule['separator'], $this->clausules[$key]);
                }
                $command[] = $clausule['instruction'] . ' ' . $value;
            }
        }
     
        $sql = implode(' ', $command);
     
        return $this->connection->executeUpdate($sql, array_merge($values, $filters));
    }

    public function delete($filters)
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
        
        foreach($clausules as $key => $clausule) {
            if (isset($this->clausules[$key])) {
                $value = $this->clausules[$key];
                if (is_array($value)) {
                    $value = implode($clausule['separator'], $this->clausules[$key]);
                }
                $command[] = $clausule['instruction'] . ' ' . $value;
            }
        }
        
        $sql = implode(' ', $command);
        
        return $this->connection->executeDelete($sql, $filters);
    }
}
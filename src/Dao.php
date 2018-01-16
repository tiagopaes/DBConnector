<?php

namespace PhpDao;

class Dao
{
    protected static $connection;

    public function __construct()
    {  
	}
	
	public static function setConnection(Connection $connection)
    {
        self::$connection = $connection;
    }

    public function getConnection()
    {
        return self::$connection;
    }

	protected $table;
	
	public function getTable()
	{
		return $this->table;
	}

	public function create(array $data)
	{
		$fields = implode(array_keys($data), ',');
		$values = [];

		foreach ($data as $key => $item) {
			$values[$key] = '?';
		}
		$values2 = implode($values, ',');

		$query = "INSERT INTO {$this->table} ({$fields}) VALUES ({$values2})";
		return $this->getConnection()->executeInsert($query, $values);
	}

    public function get($id = null)
    {
		if (!$id) {
			return $this->queryBuilder
				->table($this->table())
        		->fields(['*'])
				->select();
		}
		
		return $this->queryBuilder
			->table($this->table())
       		->fields(['*'])
       		->where(['id = ?'])
       		->select([$id])[0];
    }

    public function update($id, $data)
    {
		$fields = array_keys($data);
		$values = [];
		
		foreach ($fields as $key => $item) {
			$values[$key] = $data[$item];
		}
		
		return $this->queryBuilder
			->table($this->table())
			->fields($fields)
			->where(['id = ?'])
			->update($values, [$id]);
    }

    public function delete($id)
    {
		return $this->queryBuilder
			->table($this->table())
			->fields($fields)
			->where(['id = ?'])
			->delete([$id]);
	}
	
	public function execute()
	{
		return $this->queryBuilder->table($this->table());
	}
}
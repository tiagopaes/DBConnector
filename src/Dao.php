<?php

namespace PhpDao;

abstract class Dao
{
    protected $queryBuilder;

    public function __construct()
    {
		$options = [
			'host' => $this->host(),
			'port' => $this->port(),
			'database' => $this->database(),
			'user' => $this->user(),
			'password' => $this->password(),
			'driver' => $this->driver()
		];

		$this->queryBuilder = Factory::createQueryBuilder($options);  
    }

    abstract public function host();
  
    abstract public function database();

    abstract public function port();
  
    abstract public function user();
  
    abstract public function password();
  
    abstract public function driver();

    abstract public function table();

    public function create(array $data)
    {
		$fields = array_keys($data);
		$values = [];
		
		foreach ($fields as $key => $item) {
			$values[$key] = $data[$item];
		}
		
		return $this->queryBuilder
			->table($this->table())
			->fields($fields)
			->insert($values);
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
	
	public function query()
	{
		return $this->queryBuilder->table($this->table());
	}
}
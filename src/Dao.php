<?php

namespace PhpDao;

class Dao extends QueryBuilder
{
	protected $table = '';

	private $data = [];

	public function __contruct(array $data = [])
	{
		$this->fill($data);
	}

	public function __get($name)
    {
		if (isset($this->data[$name])) {
			return $this->data[$name];
		}

		return null;
	}
	
    public function __set($name, $value)
    {
        $this->data[$name] = $value;
    }
	
	public function getTable()
	{
		return $this->table;
	}

	protected function getClassName()
	{
        return get_class($this);
	}
	
	public function fill(array $data)
	{
		foreach ($data as $key => $value) {
			$this->data[$key] = $value;
		}
	}

	private function getFields()
	{
		return array_keys($this->data);
	}

	public function all()
	{
		return $this->table($this->getTable())
				->select();
	}

	public function find($id)
	{
		return $this->table($this->getTable())
		   ->where(['id = ?'])
		   ->select([$id]);
	}

	public function save(array $data = [])
	{
		$this->fill($data);

		if (isset($this->data['id'])) {
			$this->table($this->getTable())
				->fields($this->getFields())
				->where(['id = ?'])
				->update($this->data, [$this->data['id']]);

			return $this->table($this->getTable())
				->fields(['*'])
				->where(['id = ?'])
				->select([$this->data['id']])[0];	
		}

		$id = $this->table($this->getTable())
			->fields($this->getFields())
			->insert($this->data);
		
		return $this->table($this->getTable())
			->fields(['*'])
			->where(['id = ?'])
			->select([$id])[0];
	}
	
	public function create(array $data)
	{
		return $this->save($data);
	}

    public function remove($id)
    {
		return $this->table($this->getTable())
			->where(['id = ?'])
			->delete([$id]);
	}
	
	public function query(string $query)
	{
		$command = ucfirst(
			strtolower( explode(' ', trim($query))[0] )
		);
		$command = 'execute' . $command;
		
		return $this->getConnection()
			->$command($query, [], $this->getClassName());
	}
}
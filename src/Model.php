<?php

namespace PhpDao;

use PhpDao\QueryBuilder;

/**
 * Class Model
 *
 * @package PhpDao
 */
class Model extends QueryBuilder
{
    /**
     * The model table name.
     *
     * @var string
     */
    protected $table = '';

    /**
     * The model properties.
     *
     * @var array
     */
    private $properties = [];

    /**
     * The Model constructor.
     *
     * @param array $data
     *
     * @return void
     */
    public function __contruct(array $data = [])
    {
        $this->fill($data);
    }

    /**
     * Implements magic method to get properties.
     *
     * @param string $name the property name.
     *
     * @return mixed Returns the property value.
     */
    public function __get(string $name)
    {
        if (isset($this->properties[$name])) {
            return $this->properties[$name];
        }
        return null;
    }

    /**
     * Implements magic method to set properties.
     *
     * @param string $name
     *
     * @param mixed $value the property value.
     *
     * @return void
     */
    public function __set($name, $value)
    {
        $this->properties[$name] = $value;
    }

    /**
     * Returns the model table name.
     *
     * @return string
     */
    public function getTable()
    {
        return $this->table;
    }

    /**
     * Fills the model properties.
     *
     * @param array $data
     *
     * @return void
     */
    public function fill(array $data)
    {
        foreach ($data as $key => $value) {
            $this->properties[$key] = $value;
        }
    }

    /**
     * Returns a list of model properties.
     *
     * @return string
     */
    private function getFields()
    {
        return array_keys($this->properties);
    }

    /**
     * Returns a list of all recorded models.
     *
     * @return array
     */
    public function all()
    {
        return $this->table($this->getTable())
                ->select();
    }

    /**
     * Returns the found model.
     *
     * @param mixed $id
     *
     * @return object|null The found model.
     */
    public function find($id)
    {
        return $this->table($this->getTable())
           ->where(['id = ?'])
           ->select([$id])[0];
    }

    /**
     * Saves a model on database.
     *
     * @param array $data
     *
     * @return object The saved model.
     */
    public function save(array $data = [])
    {
        $this->fill($data);

        if (isset($this->properties['id'])) {
            $this->table($this->getTable())
                ->fields($this->getFields())
                ->where(['id = ?'])
                ->update($this->properties, [$this->properties['id']]);

            return $this->table($this->getTable())
                ->fields(['*'])
                ->where(['id = ?'])
                ->select([$this->properties['id']])[0];
        }

        $id = $this->table($this->getTable())
            ->fields($this->getFields())
            ->insert($this->properties);
        
        return $this->table($this->getTable())
            ->fields(['*'])
            ->where(['id = ?'])
            ->select([$id])[0];
    }

    /**
     * Saves a model on database.
     *
     * @param array $data
     *
     * @return object The saved model.
     */
    public function create(array $data)
    {
        return $this->save($data);
    }

    /**
     * Removes a model from database.
     *
     * @param mixed $id
     *
     * @return int 0 if failed or 1 if success.
     */
    public function remove($id)
    {
        return $this->table($this->getTable())
            ->where(['id = ?'])
            ->delete([$id]);
    }
}

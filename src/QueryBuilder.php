<?php

namespace PhpDao;

use Exception;
use PDO;

abstract class QueryBuilder
{
    protected $database;

    public function __construct(Connection $connection = null)
    {
      $this->database = $connection ? $connection->getConnection() : null;
    }

    abstract function table();

    public function getColumns(array $data)
    {
      $columns = '';
      foreach ($data as $key => $value) {
        $columns .= $key . ', ';
      }
      return substr($columns, 0, -2);
    }

    public function getCustomColumns(array $data)
    {
      $customColumns = '';
      foreach ($data as $key => $value) {
        $customColumns .= ':' . $key . ', ';
      }
      return substr($customColumns, 0, -2);
    }

    public function getCustomArray(array $data)
    {
      $customColumns = $this->getCustomColumns($data);
      $keys = explode(", ", $customColumns);
      $customArray = array_fill_keys($keys, '');
      $result = [];

      foreach ($data as $key => $value) {
        foreach ($customArray as $keyVal => $val) {
          if ($key == substr($keyVal, 1)) {
            $result[$keyVal] = $value;
            break;
          }
        }
      }

      return $result;
    }

    public function getUpdateSetString(array $data)
    {
      $string = '';
      foreach ($data as $key => $value) {
        $string .= '' . $key . ' =:' . $key . ', ';
      }
      return substr($string, 0, -2);
    }

    public function create(array $data)
    {
      $columns = $this->getColumns($data);
      $customColumns = $this->getCustomColumns($data);
      $customArray = $this->getCustomArray($data);
      $table = $this->table();
      $query = 'INSERT INTO ' . $table .
            ' (' . $columns . ') VALUES (' . $customColumns . ');';

      $this->database->beginTransaction();

      try {
        $sql = $this->database->prepare($query);
        $sql->execute($customArray);
        $this->database->commit();
      } catch (Exception $error) {
          $this->database->rollback();
          return $error->getMessage();
        }
    }

    public function all()
    {
        $query = 'SELECT * FROM ' . $this->table() . ';';

        $this->database->beginTransaction();

        try {
           $sql = $this->database->prepare($query);
           $sql->execute();
           $this->database->commit();
           $data = $sql->fetchAll(PDO::FETCH_ASSOC);
           return $data;
        } catch (Exception $error) {
            $this->database->rollback();
            return $error->getMessage();
        }
    }

    public function get($id)
    {
        $query = 'SELECT * FROM ' . $this->table() .
              ' WHERE id = :id;';

        $this->database->beginTransaction();

        try {
           $sql = $this->database->prepare($query);
           $sql->execute([':id' => $id]);
           $this->database->commit();
           $data = $sql->fetchAll(PDO::FETCH_ASSOC);
           return $data[0];
        } catch (Exception $error) {
            $this->database->rollback();
            return $error->getMessage();
        }
    }

    public function update(array $data, $id)
    {
        $columns = $this->getColumns($data);
        $customColumns = $this->getCustomColumns($data);
        $customArray = $this->getCustomArray($data);
        $table = $this->table();
        $setString = $this->getUpdateSetString($data);
        $query = 'UPDATE ' . $table . ' SET ' .
              $setString . ' WHERE id = :id;';

        $this->database->beginTransaction();

        try {
           $sql = $this->database->prepare($query);
           $sql->execute(array_merge($customArray, [':id' => $id]));
           $this->database->commit();
           return true;
        } catch (Exception $error) {
            $this->database->rollback();
            return $error->getMessage();
        }
    }

    public function delete($id)
    {
        $query = 'DELETE FROM ' . $this->table() . ' WHERE id = :id;';

        $this->database->beginTransaction();

        try {
           $sql = $this->database->prepare($query);
           $sql->execute([':id' => $id]);
           $this->database->commit();
           return true;
        } catch (Exception $error) {
            $this->database->rollback();
            return $error->getMessage();
        }
    }
}

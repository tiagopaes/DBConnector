<?php

namespace DBConnector;

abstract class DatabaseConnection
{
    protected $database;
    protected $host;
    protected $name;
    protected $user;
    protected $password;
    protected $driver;

    public function getConnection()
    {
        $driver = $this->driver();
        $host = $this->host();
        $name = $this->name();
        $user = $this->user();
        $pass = $this->password();

        try {
          $this->database = new \PDO("$driver:host=$host; dbname=$name", $user, $pass);
          $this->database->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
          $this->database->exec('SET NAMES utf8');
        } catch (\PDOException $error) {
            die("Connection Error: " . $error->getMessage());
          }

        return $this->database;
    }

    abstract function host();

    abstract function name();

    abstract function user();

    abstract function password();

    abstract function driver();
}

<?php

require 'vendor/autoload.php';

class testDb extends \DBConnector\DatabaseConnection
{
  public function host()
  {
    return 'localhost';
  }

  public function name()
  {
    return 'ranking';
  }

  public function user()
  {
    return 'tiago';
  }

  public function password()
  {
    return '';
  }

  public function driver()
  {
    return 'mysql';
  }
}

class DAOConcreteClass extends \DBConnector\DataAccessObject
{
  public function table()
  {
    return 'users';
  }
}

$test = new DAOConcreteClass(new testDb());

$test->create(['token' => '123']);

print_r($test->get(3));

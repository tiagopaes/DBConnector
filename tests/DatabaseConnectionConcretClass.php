<?php

namespace PhpDao;

class DatabaseConnectionConcretClass extends DatabaseConnection
{
  public function host()
  {
    return 'localhost';
  }

  public function name()
  {
    return 'dbName';
  }

  public function user()
  {
    return 'dbUser';
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

<?php

use PhpDao\Dao;

abstract class BaseDao extends Dao
{
    public function host()
    {
      return 'localhost';
    }
  
    public function database()
    {
      return 'qa_tools';
    }
  
    public function user()
    {
      return 'tiago';
    }
    public function port()
    {
        return '3306';
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
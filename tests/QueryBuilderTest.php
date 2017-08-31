<?php

namespace PhpDao;

use PHPUnit\Framework\TestCase;

class DataAccessObjectTest extends TestCase
{
  public function testShouldReturnTheTableName()
  {
    $dao = new DAOConcreteClass();
    $this->assertEquals('table', $dao->table());
  }

  public function testShouldReturnStringWithTablesColumnsFromDataArray()
  {
    $dao = new DAOConcreteClass();
    $data = ['name' => 'test name', 'column' => 'column value'];
    $this->assertEquals('name, column', $dao->getColumns($data));
  }

  public function testShouldReturnStringWithCustomColumnsFromDataArray()
  {
    $dao = new DAOConcreteClass();
    $data = ['name' => 'test name', 'column' => 'column value'];
    $this->assertEquals(':name, :column', $dao->getCustomColumns($data));
  }

  public function testShouldReturnCustomArrayFromDataArray()
  {
    $dao = new DAOConcreteClass();
    $data = ['name' => 'test name', 'column' => 'column value'];
    $expected = [':name' => 'test name', ':column' => 'column value'];
    $this->assertEquals($expected, $dao->getCustomArray($data));
  }

  public function testShouldReturnUpdateSetStringFromDataArray()
  {
    $dao = new DAOConcreteClass();
    $data = ['name' => 'test name', 'column' => 'column value'];
    $expected = 'name =:name, column =:column';
    $this->assertEquals($expected, $dao->getUpdateSetString($data));
  }
}

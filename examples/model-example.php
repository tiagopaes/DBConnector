<?php
require __DIR__ . '/../../vendor/autoload.php';
//require '../../vendor/autoload.php';
//require './class/BaseDao.php';
require './class/UserDao.php';

use PhpDao\Connection;
use PhpDao\Dao;

$options = [
    'host' => 'localhost',
    'database' => 'ranking',
    'user' => 'tiago',
    'password' => '',
    'port' => '3306',
    'driver' => 'mysql'
];

$pdo = new PDO(
    "mysql:host={$options['host']};port={$options['port']};dbname={$options['database']}",
    $options['user'],
    $options['password']
);
$pdo->setAttribute(
    PDO::ATTR_ERRMODE,
    PDO::ERRMODE_EXCEPTION
);

//Instance the Connection class and pass the PDO object on constructor
$connection = new Connection($pdo);
Dao::setConnection($connection);


// Instance the Dao class created
$userDao = new UserDao();

// insert example 
//$result = $userDao->find(7);
//$result->password = 'updated';
// $result = $userDao->create([
//     'token' => 'token maroto',
//     'email' => 'live@live.com',
//     'password' => 'updated 2'
//     ]);
// $id = $result->id;
// print_r($result);
// print_r('---------------------');

//$userDao->delete(7);
// $result = $userDao->find($id);
//$result = $userDao->find(7);


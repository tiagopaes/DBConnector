<?php
require __DIR__ . '/../../vendor/autoload.php';
//require '../../vendor/autoload.php';
require './class/BaseDao.php';
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
$insertedId = $userDao->create([
     'email' => 'email@test.com',
     'password' => 'password',
     'token' => 'token'
]);

print_r($insertedId);
// select examples

//get all records
// $users = $userDao->get();

// //get only one record by id
// $id = 1;
// $user = $userDao->get($id);

// //update example
// $data = [
//     'email' => 'newemail@test.com'
// ];
// $userDao->update($id, $data);

// //delete by id example
// $userDao->delete($id);
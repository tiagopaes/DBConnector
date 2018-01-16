<?php

require __DIR__ . '/../vendor/autoload.php';

use PhpDao\Connection;
use PhpDao\QueryBuilder;

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

//Sets the connection to QueryBuilder class
QueryBuilder::setConnection($connection);

//Instance the QueryBuilder class
$queryBuilder = new QueryBuilder();


// insert example 
$insertedId = $queryBuilder->table('users')
    ->fields(['token', 'email', 'password'])
    ->insert(['asdasd','email@test.com', 'password']);

//select examples
$tableValues = $queryBuilder->table('users')
    ->fields(['*'])
    ->select([]);

$tableValues = $queryBuilder->table('users')
    ->fields(['email', 'id', 'password'])
    ->where('email = ?')
    ->limit('1')
    ->select(['email@test.com']);

// update example
$queryBuilder->table('users')
    ->fields(['email'])
    ->where('id = ?')
    ->update(['email@updated.com'], [$insertedId]);

// delete example
$tableValues = $queryBuilder->table('users')
    ->where(['id = ?'])
    ->delete([$insertedId]);

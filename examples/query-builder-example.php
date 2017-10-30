<?php

require '../vendor/autoload.php';

use PhpDao\Connection;
use PhpDao\QueryBuilder;

$options = [
    'host' => 'localhost',
    'database' => 'your_database_name',
    'user' => 'root',
    'password' => '',
    'port' => '3306',
    'driver' => 'mysql'
];

//Instance the Connection class and pass the database options on constructor
$connection = new Connection($options);

//Instance the QueryBuilder class and pass the connection class on constructor
$queryBuilder = new QueryBuilder($connection);

// insert example 
$insertedId = $queryBuilder->table('users')
    ->fields(['email', 'password'])
    ->insert(['email@test.com', crypt('password')]);

//select examples
$tableValues = $queryBuilder->table('users')
    ->fields(['*'])
    ->select([]);

$tableValues = $queryBuilder->table('users')
    ->fields(['email'])
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
    ->where('id = ?')
    ->delete([$insertedId]);
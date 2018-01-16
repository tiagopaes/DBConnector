<?php

require __DIR__ . '/../vendor/autoload.php';

use PhpDao\Connection;
use PhpDao\Model;

$options = [
    'host' => 'localhost',
    'database' => 'your-database-name',
    'user' => 'username',
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

//Sets the connection to Model Object
Model::setConnection($connection);

class User extends Model
{
    protected $table = 'users';
}

// Instance the User class model created
$user = new User();

//Creates a new user on database
$userCreated = $user->create([
    'token' => 'token maroto',
    'email' => 'live@live.com',
    'password' => 'updated 2'
]);

//Creates a new user on database other way
$user = new User();
$user->token = 'token';
$user->email = 'email';
$user->password = 'password';
$user->save();

// get all users recorded
$users = $user->all();

// get only one user
$user = $user->find($id);

//update a model
$user->save([
    'token' => 'updated',
    'email' => 'updated',
    'password' => 'updated'
]);

// Delete a model
$user->remove($id);

<?php

require __DIR__ . '/../vendor/autoload.php';

use PhpDao\Connection;
use PhpDao\Model;

$options = [
    'host' => 'localhost',
    'database' => 'test',
    'user' => 'root',
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
    protected $primary_key = 'id_user';
}

// Instance the User class model created
$user = new User();

//Creates a new user on database
$userCreated = $user->create([
    'name_user' => 'live@live.com'
]);

//Creates a new user on database other way
$user->name_user = 'Guirerume';
$user->save();
// get all users recorded
$users = $user->all();

// get only one user
$user = $user->find($users[0]->id_user);

//update a model
$user->save([
    'name_user' => 'live@dasdasdasd.com'
]);

// Delete a model
$user->remove($users[0]->id_user);

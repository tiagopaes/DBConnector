<?php

require '../../vendor/autoload.php';
require './class/BaseDao.php';
require './class/UserDao.php';

// Instance the Dao class created
$userDao = new UserDao();

// insert example 
$insertedId = $userDao->create([
     'email' => 'email@test.com',
     'password' => crypt('password')
]);

// select examples

//get all records
$users = $userDao->get();

//get only one record by id
$id = 1;
$user = $userDao->get($id);

//update example
$data = [
    'email' => 'newemail@test.com'
];
$userDao->update($id, $data);

//delete by id example
$userDao->delete($id);
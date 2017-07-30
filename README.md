# PHP-DAO
 Abstraction of the DAO pattern using PDO.

## Getting Started

This project is a small library made to facilitate the data access in php aplications using PDO.


## Installing

You can install easily using composer.

```
composer require tiagopaes/php-dao
```

## How to use

You can use this lib easy way. Do you should create your class to database connection, extend the `DatabaseConnection` class and implement the abstract methods required. 

```
<?php

require 'vendor/autoload.php';

use PhpDao\DatabaseConnection;

class YourConnectionClass extends DatabaseConnection
{
  public function host()
  {
    return 'localhost';
  }

  public function name()
  {
    return 'your-database-name';
  }

  public function user()
  {
    return 'your-database-user';
  }

  public function password()
  {
    return 'your-database-password';
  }

  public function driver()
  {
    return 'mysql'; //database driver
  }
}
```

Now you can create your DAO (Data Access Object) classes, extends the `DataAccessObject` class and implement the abstract methods required.


```
<?php

require 'vendor/autoload.php';

use PhpDao\DataAccessObject;

class UserDAO extends DataAccessObject
{
  public function table()
  {
    return 'users'; //this method should return a string with the table name on database
  }
}
```

Done this, to access the data you need to instantiate your DAO class and pass an instance of your connection class as parameter.

```
<?php

// require your classes

$userDao = new UserDAO(new YourConnectionClass());

```

Now the following methods are availabel to you use: create, update, all, get and delete.

#### Create
```
<?php

// require your classes

$userDao = new UserDAO(new YourConnectionClass());

$data = ['name' => 'userName', 'email'=>'user@example.com'];
$userDao->create($data); //create a new record on database

```

#### All
```
<?php

// require your classes

$userDao = new UserDAO(new YourConnectionClass());

$userDao->all(); //return an array with all records of the database table

```

#### Get
```
<?php

// require your classes

$userDao = new UserDAO(new YourConnectionClass());
$id = 1;
$userDao->get($id); //return only one record of the database table by id

```

#### Update
```
<?php

// require your classes

$userDao = new UserDAO(new YourConnectionClass());
$id = 1;
$newData = ['name' => 'new userName', 'email'=>'newuser@example.com'];
$userDao->update($newData, $id); //update the record of the database table by id

```


#### Delete
```
<?php

// require your classes

$userDao = new UserDAO(new YourConnectionClass());
$id = 1;
$userDao->delete($id); //delete the record of the database table by id

```

## Versioning

We use [SemVer](http://semver.org/) for versioning. For the versions available, see the [tags on this repository](https://github.com/tiagopaes/php-dao/tags). 


## Contributing

Please read [CONTRIBUTING.md](https://github.com/tiagopaes/php-dao/blob/master/CONTRIBUTING.md) for details on our code of conduct, and the process for submitting pull requests to us.

## License

This project is licensed under the MIT License - see the [LICENSE.md](LICENSE.md) file for details.

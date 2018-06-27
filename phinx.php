<?php

require 'vendor/autoload.php';

$dotenv = new Dotenv\Dotenv(__DIR__);
$dotenv->load();

return array(
    'paths' => array(
        'migrations' => 'database/migrations'
    ),
    'environments' => array(
        'default_migration_table' => 'phinxlog',
        'default_database' => 'dev',
        'dev' => array(
            'adapter' => getenv('PHPDAO_DB_DRIVER'),
            'host' => getenv('PHPDAO_DB_HOST'),
            'name' => getenv('PHPDAO_DB_DATABASE'),
            'user' => getenv('PHPDAO_DB_USER'),
            'pass' => getenv('PHPDAO_DB_PASSWORD'),
            'port' => getenv('PHPDAO_DB_PORT')
        )
    )
);

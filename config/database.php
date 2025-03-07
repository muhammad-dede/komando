<?php

return [

    /*
    |--------------------------------------------------------------------------
    | PDO Fetch Style
    |--------------------------------------------------------------------------
    |
    | By default, database results will be returned as instances of the PHP
    | stdClass object; however, you may desire to retrieve records in an
    | array format for simplicity. Here you can tweak the fetch style.
    |
    */

    'fetch' => PDO::FETCH_CLASS,

    /*
    |--------------------------------------------------------------------------
    | Default Database Connection Name
    |--------------------------------------------------------------------------
    |
    | Here you may specify which of the database connections below you wish
    | to use as your default connection for all database work. Of course
    | you may use many connections at once using the Database library.
    |
    */

    'default' => env('DB_CONNECTION', 'mysql'),

    /*
    |--------------------------------------------------------------------------
    | Database Connections
    |--------------------------------------------------------------------------
    |
    | Here are each of the database connections setup for your application.
    | Of course, examples of configuring each database platform that is
    | supported by Laravel is shown below to make development simple.
    |
    |
    | All database work in Laravel is done through the PHP PDO facilities
    | so make sure you have the driver for your particular database of
    | choice installed on your machine before you begin development.
    |
    */

    'connections' => [

        'sqlite' => [
            'driver' => 'sqlite',
            'database' => env('DB_DATABASE', database_path('database.sqlite')),
            'prefix' => '',
        ],

        'mysql' => [
            'driver' => 'mysql',
            'host' => env('DB_HOST', 'localhost'),
            'port' => env('DB_PORT', '3306'),
            'database' => env('DB_DATABASE', 'forge'),
            'username' => env('DB_USERNAME', 'forge'),
            'password' => env('DB_PASSWORD', ''),
            'charset' => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix' => '',
            'strict' => false,
            'engine' => null,
        ],

        'pgsql' => [
            'driver' => 'pgsql',
            'host' => env('DB_HOST', 'localhost'),
            'port' => env('DB_PORT', '5432'),
            'database' => env('DB_DATABASE', 'forge'),
            'username' => env('DB_USERNAME', 'forge'),
            'password' => env('DB_PASSWORD', ''),
            'charset' => 'utf8',
            'prefix' => '',
            'schema' => 'public',
        ],

        'oracle' => [
            'driver'        => 'oracle',
        //'tns' => '(DESCRIPTION=(ADDRESS=(PROTOCOL=TCP)(HOST=10.1.18.127)(PORT=1521)) (CONNECT_DATA=(SERVICE_NAME=simpus)))',
            'tns'           => env('DB_TNS', ''),
            'host'          => env('DB_HOST', ''),
            'port'          => env('DB_PORT', '1521'),
            'database'      => env('DB_DATABASE', ''),
            'username'      => env('DB_USERNAME', ''),
            'password'      => env('DB_PASSWORD', ''),
            'charset'       => env('DB_CHARSET', 'AL32UTF8'),
            'prefix'        => env('DB_PREFIX', ''),
            'prefix_schema' => env('DB_SCHEMA_PREFIX', ''),
        ],

        'sap_rr' => [
            'driver'        => 'oracle',
            'tns'           => env('DB_TNS_RR', ''),
            'host'          => env('DB_HOST_RR', ''),
            'port'          => env('DB_PORT_RR', '1521'),
            'database'      => env('DB_DATABASE_RR', ''),
            'username'      => env('DB_USERNAME_RR', ''),
            'password'      => env('DB_PASSWORD_RR', ''),
            'charset'       => env('DB_CHARSET_RR', 'AL32UTF8'),
            'prefix'        => env('DB_PREFIX_RR', ''),
            'prefix_schema' => env('DB_SCHEMA_PREFIX_RR', ''),
        ],

        'prod' => [
            'driver'        => 'oracle',
            'tns'           => env('DB_TNS_PR', ''),
        //'tns' => '(DESCRIPTION=(ADDRESS=(PROTOCOL=TCP)(HOST=10.1.18.127)(PORT=1521)) (CONNECT_DATA=(SERVICE_NAME=simpus)))',
            'host'          => env('DB_HOST_PR', '10.1.18.127'),
            'port'          => env('DB_PORT_PR', '1521'),
            'database'      => env('DB_DATABASE_PR', 'simpus'),
            'username'      => env('DB_USERNAME_PR', 'budaya'),
            'password'      => env('DB_PASSWORD_PR', 'fK0bkfPvJwtL7yMP2Urr'),
            'charset'       => env('DB_CHARSET_PR', 'AL32UTF8'),
            'prefix'        => env('DB_PREFIX_PR', ''),
            'prefix_schema' => env('DB_SCHEMA_PREFIX_PR', ''),
        ],

        'trn' => [
            'driver'        => 'oracle',
            'tns'           => env('DB_TNS_TRN', ''),
            'host'          => env('DB_HOST_TRN', '10.1.18.127'),
            'port'          => env('DB_PORT_TRN', '1521'),
            'database'      => env('DB_DATABASE_TRN', 'simpus'),
            'username'      => env('DB_USERNAME_TRN', 'coc_dev'),
            'password'      => env('DB_PASSWORD_TRN', 'coc_dev'),
            'charset'       => env('DB_CHARSET_TRN', 'AL32UTF8'),
            'prefix'        => env('DB_PREFIX_TRN', ''),
            'prefix_schema' => env('DB_SCHEMA_PREFIX_TRN', ''),
        ],

        'dev' => [
            'driver'        => 'oracle',
            'tns'           => env('DB_TNS_DEV', ''),
            'host'          => env('DB_HOST_DEV', 'localhost'),
            'port'          => env('DB_PORT_DEV', '1521'),
            'database'      => env('DB_DATABASE_DEV', 'xe'),
            'username'      => env('DB_USERNAME_DEV', 'coc_local'),
            'password'      => env('DB_PASSWORD_DEV', 'coc_local#1'),
            'charset'       => env('DB_CHARSET_DEV', 'AL32UTF8'),
            'prefix'        => env('DB_PREFIX_DEV', ''),
            'prefix_schema' => env('DB_SCHEMA_PREFIX_DEV', ''),
        ],

    'default' => [
            'host' => env('REDIS_HOST', 'tcp://127.0.0.1'),
            'password' => env('REDIS_PASSWORD', null),
            'port' => env('REDIS_PORT', 6379),
            'database' => 0,
        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Migration Repository Table
    |--------------------------------------------------------------------------
    |
    | This table keeps track of all the migrations that have already run for
    | your application. Using this information, we can determine which of
    | the migrations on disk haven't actually been run in the database.
    |
    */

    'migrations' => 'migrations',

    /*
    |--------------------------------------------------------------------------
    | Redis Databases
    |--------------------------------------------------------------------------
    |
    | Redis is an open source, fast, and advanced key-value store that also
    | provides a richer set of commands than a typical key-value systems
    | such as APC or Memcached. Laravel makes it easy to dig right in.
    |
    */

    'redis' => [

        'cluster' => false,

        'default' => [
            'host' => env('REDIS_HOST', 'localhost'),
            'password' => env('REDIS_PASSWORD', null),
            'port' => env('REDIS_PORT', 6379),
            'database' => 0,
        ],

    ],

];

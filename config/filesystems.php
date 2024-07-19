<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Filesystem Disk
    |--------------------------------------------------------------------------
    |
    | Here you may specify the default filesystem disk that should be used
    | by the framework. A "local" driver, as well as a variety of cloud
    | based drivers are available for your choosing. Just store away!
    |
    | Supported: "local", "ftp", "s3", "rackspace"
    |
    */

    'default' => env('DEFAULT_STORAGE', 'local'),

    /*
    |--------------------------------------------------------------------------
    | Default Cloud Filesystem Disk
    |--------------------------------------------------------------------------
    |
    | Many applications store files both locally and in the cloud. For this
    | reason, you may specify a default "cloud" driver here. This driver
    | will be bound as the Cloud disk implementation in the container.
    |
    */

    'cloud' => 's3',

    /*
    |--------------------------------------------------------------------------
    | Filesystem Disks
    |--------------------------------------------------------------------------
    |
    | Here you may configure as many filesystem "disks" as you wish, and you
    | may even configure multiple disks of the same driver. Defaults have
    | been setup for each driver as an example of the required options.
    |
    */

    'disks' => [

        'local' => [
            'driver' => 'local',
            'root' => storage_path('app'),
        ],

        'public' => [
            'driver' => 'local',
            'root' => storage_path('app/public'),
            'visibility' => 'public',
        ],

        'media' => [
            'driver' => 'local',
            'root' => storage_path('app/public/media'),
        ],

        'ftp' => [
            'driver'   => 'ftp',
            'host'     => '10.1.18.76',
            'username' => 'mercusuar',
            'password' => 'P@ssw0rd',

            // Optional FTP Settings...
            // 'port'     => 21,
            // 'root'     => '',
            // 'passive'  => true,
            // 'ssl'      => true,
            // 'timeout'  => 30,
        ],

        'ftp_sap' => [
            'driver'   => 'ftp',
            'host'     => env('FTP_SAP', '10.1.6.22'),
            'username' => 'ifcadm',
            'password' => 'simba',

            // Optional FTP Settings...
//             'port'     => 22,
            // 'root'     => '',
            // 'passive'  => true,
            // 'ssl'      => true,
            // 'timeout'  => 30,
        ],

        'ftp_interface' => [
            'driver'   => 'ftp',
            'host'     => '10.1.18.195',
            'username' => 'interface',
            'password' => 'interface',

            // Optional FTP Settings...
//             'port'     => 22,
            // 'root'     => '',
            // 'passive'  => true,
            // 'ssl'      => true,
            // 'timeout'  => 30,
        ],

        'ftp_plnpusat' => [
            'driver'   => 'ftp',
            'host'     => '10.1.18.195',
            'username' => 'plnpusat',
            'password' => 'M@shudi',

            // Optional FTP Settings...
//             'port'     => 22,
            // 'root'     => '',
            // 'passive'  => true,
            // 'ssl'      => true,
            // 'timeout'  => 30,
        ],

        's3' => [
            'driver' => 's3',
            'key' => 'your-key',
            'secret' => 'your-secret',
            'region' => 'your-region',
            'bucket' => 'your-bucket',
        ],

    ],

];

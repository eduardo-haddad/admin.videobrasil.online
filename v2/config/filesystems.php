<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Filesystem Disk
    |--------------------------------------------------------------------------
    |
    | Here you may specify the default filesystem disk that should be used
    | by the framework. The "local" disk, as well as a variety of cloud
    | based disks are available to your application. Just store away!
    |
    */

    'default' => env('FILESYSTEM_DRIVER', 'public'),

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

    'cloud' => env('FILESYSTEM_CLOUD', 's3'),

    /*
    |--------------------------------------------------------------------------
    | Filesystem Disks
    |--------------------------------------------------------------------------
    |
    | Here you may configure as many filesystem "disks" as you wish, and you
    | may even configure multiple disks of the same driver. Defaults have
    | been setup for each driver as an example of the required options.
    |
    | Supported Drivers: "local", "ftp", "s3", "rackspace"
    |
    */

    'disks' => [

        'local' => [
            'driver' => 'local',
            'root' => env('APP_ENV') == 'local' ? storage_path('app') : base_path('../../storage/app'),
        ],

        'public' => [
            'driver' => 'local',
            'root' => env('APP_ENV') == 'local' ? storage_path('app/public') : base_path('../../storage/app/public'),
            'url' => env('APP_URL') . '/storage',
            'visibility' => 'public',
        ],

        'logs' => [
            'driver' => 'local',
            'root' => env('APP_ENV') == 'local' ? storage_path('logs') : base_path('../../storage/logs'),
            'url' => env('APP_URL') . '/storage',
            'visibility' => 'public',
        ],

        'output' => [
            'driver' => 'local',
            'root' => env('APP_ENV') == 'local' ? '/var/www/output/sitemap' : '/var/www/vhosts/agenteimovel.com/ec2/output/sitemap/',
            'url' => env('APP_URL') . '/',
            'visibility' => 'public',
        ],

        'parser' => [
            'driver' => 'local',
            'root' => env('APP_ENV') == 'local' ? '/var/www/prodparser/data/storage/client/' : '/var/www/vhosts/agenteimovel.com/prodparser/data/storage/client/',
            'url' => env('APP_URL') . '/',
            'visibility' => 'public',
        ],

        'images' => [
            'driver' => 'local',
            'root' => env('APP_ENV') == 'prod' || env('APP_ENV') == 'staging' ? env('IMAGE_DIR') : storage_path('app/public/images'),
            'url' => env('APP_URL') . '/',
            'visibility' => 'public',
        ],

        'cdn' => [
          'driver' => 'local',
          'root' => base_path('../../media/uploads'),
          'url' => env('CDN_URL') . '/uploads',
          'visibility' => 'public'
        ],

        's3' => [
            'driver' => 's3',
            'key' => env('AWS_ACCESS_KEY_ID'),
            'secret' => env('AWS_SECRET_ACCESS_KEY'),
            'region' => env('AWS_DEFAULT_REGION'),
            'bucket' => env('AWS_BUCKET'),
        ],

    ],

];

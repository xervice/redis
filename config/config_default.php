<?php


use Xervice\DataProvider\DataProviderConfig;
use Xervice\Redis\RedisConfig;

$config[DataProviderConfig::DATA_PROVIDER_GENERATED_PATH] = dirname(__DIR__) . '/src/Generated';
$config[DataProviderConfig::DATA_PROVIDER_PATHS] = [
    dirname(__DIR__) . '/src/',
    dirname(__DIR__) . '/vendor/',
];

if (class_exists(RedisConfig::class)) {
// Config
    $config[RedisConfig::REDIS_HOST] = '127.0.0.1';
    $config[RedisConfig::REDIS_PORT] = 6379;
    $config[RedisConfig::REDIS_PASSWORD] = '';
    $config[RedisConfig::REDIS_DATABASE] = 0;


// Static
    $config[RedisConfig::REDIS_OPTIONS] = [
        'service'    => 'master',
        'parameters' => [
            'database' => $config[RedisConfig::REDIS_DATABASE]
        ]
    ];

    if ($config[RedisConfig::REDIS_PASSWORD]) {
        $config[RedisConfig::REDIS_OPTIONS]['parameters']['password'] = $config[RedisConfig::REDIS_PASSWORD];
    }

    $config[RedisConfig::REDIS] = [
        'schema' => 'tcp',
        'host'   => $config[RedisConfig::REDIS_HOST],
        'port'   => $config[RedisConfig::REDIS_PORT]
    ];
}
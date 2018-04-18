Xervice: Redis
======

Redis client for Xervice components based on DataProvider.

[RedisFacade]->init();  
  
[RedisClient]->set('name', new AbstractDataProvider());  
[RedisClient]->get('name');


Config
----
```php
<?php

use Xervice\Redis\RedisConfig;

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
```
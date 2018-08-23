Xervice: Redis
======

[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/xervice/redis/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/xervice/redis/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/xervice/redis/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/xervice/redis/?branch=master)

Redis client for Xervice components based on DataProvider.


Installation
--------------
```
composer install xervice/redis
```

To use redis, you must add the plugin \Xervice\Redis\Communication\Plugin\RedisService to your kernel stack.
If you want to use redis as session storage, you can change the sessionhandler to \Xervice\Redis\Communication\Plugin\RedisSessionHandler.


Configure
----------
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


Usage
------
```php
Locator::getInstance()->redis()->facade()->init();

Locator::getInstance()->redis()->facade()->set(...);
Locator::getInstance()->redis()->facade()->get(...);
Locator::getInstance()->redis()->facade()->mget(...);
Locator::getInstance()->redis()->facade()->mset(...);
//...
```
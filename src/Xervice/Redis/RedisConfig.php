<?php


namespace Xervice\Redis;


use Xervice\Core\Config\AbstractConfig;

class RedisConfig extends AbstractConfig
{
    const REDIS = 'redis';

    const REDIS_OPTIONS = 'redis.options';

    const REDIS_HOST = 'redis.host';

    const REDIS_PORT = 'redis.port';

    const REDIS_PASSWORD = 'redis.password';

    const REDIS_DATABASE = 'redis.database';

    /**
     * @return array
     * @throws \Xervice\Config\Exception\ConfigNotFound
     */
    public function getRedisConfig()
    {
        return $this->get(self::REDIS);
    }

    /**
     * @return array
     * @throws \Xervice\Config\Exception\ConfigNotFound
     */
    public function getRedisOptions()
    {
        return $this->get(self::REDIS_OPTIONS);
    }
}
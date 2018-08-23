<?php
declare(strict_types=1);


namespace Xervice\Redis;


use Xervice\Core\Business\Model\Config\AbstractConfig;

class RedisConfig extends AbstractConfig
{
    public const REDIS = 'redis';

    public const REDIS_OPTIONS = 'redis.options';

    public const REDIS_HOST = 'redis.host';

    public const REDIS_PORT = 'redis.port';

    public const REDIS_PASSWORD = 'redis.password';

    public const REDIS_DATABASE = 'redis.database';

    /**
     * @return array
     */
    public function getRedisConfig(): array
    {
        return $this->get(self::REDIS);
    }

    /**
     * @return array
     */
    public function getRedisOptions(): array
    {
        return $this->get(self::REDIS_OPTIONS);
    }
}

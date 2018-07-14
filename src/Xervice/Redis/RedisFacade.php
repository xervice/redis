<?php
declare(strict_types=1);


namespace Xervice\Redis;


use Xervice\Core\Facade\AbstractFacade;

/**
 * @method \Xervice\Redis\RedisFactory getFactory()
 * @method \Xervice\Redis\RedisConfig getConfig()
 * @method \Xervice\Redis\RedisClient getClient()
 */
class RedisFacade extends AbstractFacade
{
    public function init(): void
    {
        $this->getFactory()->createCommandProvider()->provideCommands();
    }

    public function flushAll(): void
    {
        $this->getFactory()->getRedisClient()->flushall();
    }
}

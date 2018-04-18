<?php


namespace Xervice\Redis;


use Xervice\Core\Facade\AbstractFacade;

/**
 * @method \Xervice\Redis\RedisFactory getFactory()
 * @method \Xervice\Redis\RedisConfig getConfig()
 * @method \Xervice\Redis\RedisClient getClient()
 */
class RedisFacade extends AbstractFacade
{
    /**
     * @throws \Xervice\Config\Exception\ConfigNotFound
     */
    public function init()
    {
        $this->getFactory()->createCommandProvider()->provideCommands();
    }

    /**
     * @throws \Xervice\Config\Exception\ConfigNotFound
     */
    public function flushAll()
    {
        $this->getFactory()->getRedisClient()->flushall();
    }
}
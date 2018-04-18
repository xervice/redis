<?php


namespace Xervice\Redis;


use Xervice\Core\Client\AbstractClient;
use Xervice\DataProvider\DataProvider\AbstractDataProvider;
use Xervice\Redis\Exception\RedisException;

/**
 * @method \Xervice\Redis\RedisFactory getFactory()
 * @method \Xervice\Redis\RedisConfig getConfig()
 */
class RedisClient extends AbstractClient
{
    /**
     * @param string $key
     * @param \Xervice\DataProvider\DataProvider\AbstractDataProvider $dataProvider
     *
     * @throws \Xervice\Config\Exception\ConfigNotFound
     */
    public function set(string $key, AbstractDataProvider $dataProvider)
    {
        $this->getFactory()->getRedisClient()->set(
            $key,
            $this->getFactory()->createConverter()->convertTo($dataProvider)
        );
    }

    /**
     * @param string $key
     *
     * @return \Xervice\DataProvider\DataProvider\AbstractDataProvider
     * @throws \Xervice\Config\Exception\ConfigNotFound
     */
    public function get(string $key)
    {
        $result = $this->getFactory()->getRedisClient()->get($key);

        if (!$result) {
            throw new RedisException("No value found for key " . $key);
        }

        return $this->getFactory()->createConverter()->convertFrom(
            $result
        );
    }

    /**
     * @param string $key
     *
     * @throws \Xervice\Config\Exception\ConfigNotFound
     */
    public function delete(string $key)
    {
        $this->bulkDelete([$key]);
    }

    /**
     * @param array $keys
     *
     * @throws \Xervice\Config\Exception\ConfigNotFound
     */
    public function bulkDelete(array $keys) {
        $this->getFactory()->getRedisClient()->del($keys);
    }
}
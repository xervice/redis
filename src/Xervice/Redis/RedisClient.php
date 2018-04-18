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
     * @param string $expireResolution
     * @param int $expireTTL
     *
     * @return mixed
     * @throws \Xervice\Config\Exception\ConfigNotFound
     */
    public function setEx(string $key, AbstractDataProvider $dataProvider, string $expireResolution, int $expireTTL)
    {
        return $this->getFactory()->getRedisClient()->set(
            $key,
            $this->getFactory()->createConverter()->convertTo($dataProvider),
            $expireResolution,
            $expireTTL
        );
    }

    /**
     * @param string $key
     * @param \Xervice\DataProvider\DataProvider\AbstractDataProvider $dataProvider
     *
     * @return mixed
     * @throws \Xervice\Config\Exception\ConfigNotFound
     */
    public function set(string $key, AbstractDataProvider $dataProvider)
    {
        return $this->getFactory()->getRedisClient()->set(
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
     * @param $key
     * @param $seconds
     *
     * @throws \Xervice\Config\Exception\ConfigNotFound
     */
    public function expire($key, $seconds)
    {
        $this->getFactory()->getRedisClient()->expire($key, $seconds);
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
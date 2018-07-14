<?php
declare(strict_types=1);


namespace Xervice\Redis;


use Xervice\Core\Client\AbstractClient;
use Xervice\DataProvider\DataProvider\AbstractDataProvider;
use Xervice\DataProvider\DataProvider\DataProviderInterface;
use Xervice\Redis\Exception\RedisException;

/**
 * @method \Xervice\Redis\RedisFactory getFactory()
 * @method \Xervice\Redis\RedisConfig getConfig()
 */
class RedisClient extends AbstractClient
{
    /**
     * Clear old transactions
     */
    public function startTransaction(): void
    {
        $this->getFactory()->getTransactionHandler()->clearCollection();
    }

    /**
     * @param string $key
     * @param \Xervice\DataProvider\DataProvider\AbstractDataProvider $dataProvider
     */
    public function addTransaction(string $key, AbstractDataProvider $dataProvider): void
    {
        $this->getFactory()->getTransactionHandler()->addToCollection(
            $this->getFactory()->createTransaction(
                $key,
                $dataProvider
            )
        );
    }

    /**
     * @param string $key
     *
     * @return bool
     */
    public function exists(string $key): bool
    {
        return $this->getFactory()->getRedisClient()->exists($key) !== 0;
    }

    /**
     * @return mixed
     */
    public function persistTransaction()
    {
        return $this->mset(
            $this->getFactory()->getTransactionHandler()->getTransactionArray()
        );
    }

    /**
     * @param string $key
     * @param \Xervice\DataProvider\DataProvider\AbstractDataProvider $dataProvider
     * @param string $expireResolution
     * @param int $expireTTL
     *
     * @return mixed
     */
    public function setEx(
        string $key,
        AbstractDataProvider $dataProvider,
        string $expireResolution,
        int $expireTTL
    ) {
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
     */
    public function set(string $key, AbstractDataProvider $dataProvider)
    {
        return $this->getFactory()->getRedisClient()->set(
            $key,
            $this->getFactory()->createConverter()->convertTo($dataProvider)
        );
    }

    /**
     * @param array $list
     *
     * @return mixed
     */
    public function mset(array $list)
    {
        return $this->getFactory()->getRedisClient()->mset(
            $this->getFactory()->createListConverter()->convertToList($list)
        );
    }

    /**
     * @param string $key
     *
     * @return \Xervice\DataProvider\DataProvider\DataProviderInterface
     * @throws \Xervice\Redis\Exception\RedisException
     */
    public function get(string $key): DataProviderInterface
    {
        $result = $this->getFactory()->getRedisClient()->get($key);

        if (!$result) {
            throw new RedisException('No value found for key ' . $key);
        }

        return $this->getFactory()->createConverter()->convertFrom(
            $result
        );
    }

    /**
     * @param array $keys
     *
     * @return array
     */
    public function mget(array $keys): array
    {
        $result = $this->getFactory()->getRedisClient()->mget($keys);

        return $this->getFactory()->createListConverter()->convertFromList(
            $result
        );
    }

    /**
     * @param $key
     * @param $seconds
     */
    public function expire($key, $seconds): void
    {
        $this->getFactory()->getRedisClient()->expire($key, $seconds);
    }

    /**
     * @param string $key
     */
    public function delete(string $key): void
    {
        $this->bulkDelete([$key]);
    }

    /**
     * @param array $keys
     */
    public function bulkDelete(array $keys): void
    {
        $this->getFactory()->getRedisClient()->del($keys);
    }
}

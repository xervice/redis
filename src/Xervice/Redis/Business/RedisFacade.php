<?php
declare(strict_types=1);


namespace Xervice\Redis\Business;

use Xervice\Core\Business\Model\Facade\AbstractFacade;
use Xervice\DataProvider\Business\Model\DataProvider\AbstractDataProvider;
use Xervice\DataProvider\Business\Model\DataProvider\DataProviderInterface;
use Xervice\Redis\Business\Exception\RedisException;

/**
 * @method \Xervice\Redis\Business\RedisBusinessFactory getFactory()
 * @method \Xervice\Redis\RedisConfig getConfig()
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

    /**
     * Clear old transactions
     */
    public function startTransaction(): void
    {
        $this->getFactory()->getTransactionHandler()->clearCollection();
    }

    /**
     * @param string $key
     * @param \Xervice\DataProvider\Business\Model\DataProvider\AbstractDataProvider $dataProvider
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
     * @param \Xervice\DataProvider\Business\Model\DataProvider\AbstractDataProvider $dataProvider
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
     * @param \Xervice\DataProvider\Business\Model\DataProvider\AbstractDataProvider $dataProvider
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
     * @return \Xervice\DataProvider\Business\Model\DataProvider\DataProviderInterface
     * @throws \Xervice\Redis\Business\Exception\RedisException
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
     * @param string $search
     *
     * @return array
     */
    public function mgetByKeys(string $search): array
    {
        return $this->mget(
            $this->keys($search)
        );
    }

    /**
     * @param string $search
     *
     * @return array
     */
    public function keys(string $search): array
    {
        return $this->getFactory()->getRedisClient()->keys($search);
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
     * @param string $key
     * @param int $seconds
     */
    public function expire(string $key, int $seconds): void
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

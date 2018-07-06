<?php


namespace Xervice\Redis;


use Predis\Client;
use Xervice\Core\Factory\AbstractFactory;
use Xervice\DataProvider\DataProvider\AbstractDataProvider;
use Xervice\Redis\Commands\Collection;
use Xervice\Redis\Commands\Provider;
use Xervice\Redis\Commands\ProviderInterface;
use Xervice\Redis\Converter\DataConverter;
use Xervice\Redis\Converter\DataConverterInterface;
use Xervice\Redis\Converter\ListConverter;
use Xervice\Redis\Converter\ListConverterInterface;
use Xervice\Redis\Transaction\Transaction;
use Xervice\Redis\Transaction\TransactionCollection;
use Xervice\Redis\Transaction\TransactionHandler;
use Xervice\Redis\Transaction\TransactionHandlerInterface;
use Xervice\Redis\Transaction\TransactionInterface;

/**
 * @method \Xervice\Redis\RedisConfig getConfig()
 */
class RedisFactory extends AbstractFactory
{
    /**
     * @var \Predis\Client
     */
    private $client;

    /**
     * @var TransactionHandlerInterface
     */
    private $transationHandler;

    /**
     * @param string $key
     * @param \Xervice\DataProvider\DataProvider\AbstractDataProvider $dataProvider
     *
     * @return \Xervice\Redis\Transaction\Transaction
     */
    public function createTransaction(string $key, AbstractDataProvider $dataProvider) : TransactionInterface
    {
        return new Transaction(
            $key,
            $dataProvider
        );
    }

    /**
     * @return \Xervice\Redis\Transaction\TransactionHandler
     */
    public function createTransactionHandler() : TransactionHandlerInterface
    {
        return new TransactionHandler(
            $this->createTransactionCollection()
        );
    }

    /**
     * @return \Xervice\Redis\Transaction\TransactionCollection
     */
    public function createTransactionCollection() : TransactionCollection
    {
        return new TransactionCollection();
    }

    /**
     * @return \Xervice\Redis\Converter\ListConverter
     */
    public function createListConverter() : ListConverterInterface
    {
        return new ListConverter(
            $this->createConverter()
        );
    }

    /**
     * @return \Xervice\Redis\Converter\DataConverter
     */
    public function createConverter(): DataConverterInterface
    {
        return new DataConverter();
    }

    /**
     * @return \Xervice\Redis\Commands\Provider
     * @throws \Xervice\Config\Exception\ConfigNotFound
     */
    public function createCommandProvider(): ProviderInterface
    {
        return new Provider(
            $this->getCommandCollection(),
            $this->getRedisClient()
        );
    }

    /**
     * @return \Predis\Client
     * @throws \Xervice\Config\Exception\ConfigNotFound
     */
    public function createRedisClient(): Client
    {
        return new Client(
            $this->getConfig()->getRedisConfig(),
            $this->getConfig()->getRedisOptions()
        );
    }

    /**
     * @return \Predis\Client
     * @throws \Xervice\Config\Exception\ConfigNotFound
     */
    public function getRedisClient(): Client
    {
        if ($this->client === null) {
            $this->client = $this->createRedisClient();
        }
        return $this->client;
    }

    /**
     * @return \Xervice\Redis\Commands\Collection
     */
    public function getCommandCollection(): Collection
    {
        return $this->getDependency(RedisDependencyProvider::REDIS_COMMAND_COLLECTION);
    }

    /**
     * @return \Xervice\Redis\Transaction\TransactionHandler
     */
    public function getTransactionHandler() : TransactionHandlerInterface
    {
        if ($this->transationHandler === null) {
            $this->transationHandler = $this->createTransactionHandler();
        }

        return $this->transationHandler;
    }
}
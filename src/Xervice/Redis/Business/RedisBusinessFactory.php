<?php
declare(strict_types=1);


namespace Xervice\Redis\Business;


use Predis\Client;
use Xervice\Core\Business\Model\Factory\AbstractBusinessFactory;
use Xervice\DataProvider\Business\Model\DataProvider\AbstractDataProvider;
use Xervice\Redis\Business\Model\Commands\Collection;
use Xervice\Redis\Business\Model\Commands\Provider;
use Xervice\Redis\Business\Model\Commands\ProviderInterface;
use Xervice\Redis\Business\Model\Converter\DataConverter;
use Xervice\Redis\Business\Model\Converter\DataConverterInterface;
use Xervice\Redis\Business\Model\Converter\ListConverter;
use Xervice\Redis\Business\Model\Converter\ListConverterInterface;
use Xervice\Redis\Business\Model\Transaction\Transaction;
use Xervice\Redis\Business\Model\Transaction\TransactionCollection;
use Xervice\Redis\Business\Model\Transaction\TransactionHandler;
use Xervice\Redis\Business\Model\Transaction\TransactionHandlerInterface;
use Xervice\Redis\Business\Model\Transaction\TransactionInterface;
use Xervice\Redis\RedisDependencyProvider;

/**
 * @method \Xervice\Redis\RedisConfig getConfig()
 */
class RedisBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @var \Predis\Client
     */
    private $client;

    /**
     * @var \Xervice\Redis\Business\Model\Transaction\TransactionHandlerInterface
     */
    private $transationHandler;

    /**
     * @param string $key
     * @param \Xervice\DataProvider\Business\Model\DataProvider\AbstractDataProvider $dataProvider
     *
     * @return \Xervice\Redis\Business\Model\Transaction\TransactionInterface
     */
    public function createTransaction(string $key, AbstractDataProvider $dataProvider) : TransactionInterface
    {
        return new Transaction(
            $key,
            $dataProvider
        );
    }

    /**
     * @return \Xervice\Redis\Business\Model\Transaction\TransactionHandlerInterface
     */
    public function createTransactionHandler() : TransactionHandlerInterface
    {
        return new TransactionHandler(
            $this->createTransactionCollection()
        );
    }

    /**
     * @return \Xervice\Redis\Business\Model\Transaction\TransactionCollection
     */
    public function createTransactionCollection() : TransactionCollection
    {
        return new TransactionCollection();
    }

    /**
     * @return \Xervice\Redis\Business\Model\Converter\ListConverterInterface
     */
    public function createListConverter() : ListConverterInterface
    {
        return new ListConverter(
            $this->createConverter()
        );
    }

    /**
     * @return \Xervice\Redis\Business\Model\Converter\DataConverterInterface
     */
    public function createConverter(): DataConverterInterface
    {
        return new DataConverter();
    }

    /**
     * @return \Xervice\Redis\Business\Model\Commands\ProviderInterface
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
     */
    public function getRedisClient(): Client
    {
        if ($this->client === null) {
            $this->client = $this->createRedisClient();
        }
        return $this->client;
    }

    /**
     * @return \Xervice\Redis\Business\Model\Commands\Collection
     */
    public function getCommandCollection(): Collection
    {
        return $this->getDependency(RedisDependencyProvider::REDIS_COMMAND_COLLECTION);
    }

    /**
     * @return \Xervice\Redis\Business\Model\Transaction\TransactionHandlerInterface
     */
    public function getTransactionHandler() : TransactionHandlerInterface
    {
        if ($this->transationHandler === null) {
            $this->transationHandler = $this->createTransactionHandler();
        }

        return $this->transationHandler;
    }
}

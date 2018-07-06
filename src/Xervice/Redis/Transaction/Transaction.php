<?php


namespace Xervice\Redis\Transaction;


use Xervice\DataProvider\DataProvider\AbstractDataProvider;

class Transaction implements TransactionInterface
{
    /**
     * @var string
     */
    private $key;

    /**
     * @var AbstractDataProvider
     */
    private $dataProvider;

    /**
     * Transaction constructor.
     *
     * @param string $key
     * @param AbstractDataProvider $dataProvider
     */
    public function __construct(string $key, AbstractDataProvider $dataProvider)
    {
        $this->key = $key;
        $this->dataProvider = $dataProvider;
    }

    /**
     * @return string
     */
    public function getKey(): string
    {
        return $this->key;
    }

    /**
     * @return AbstractDataProvider
     */
    public function getDataProvider(): AbstractDataProvider
    {
        return $this->dataProvider;
    }
}
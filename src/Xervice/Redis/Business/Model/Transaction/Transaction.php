<?php
declare(strict_types=1);


namespace Xervice\Redis\Business\Model\Transaction;


use Xervice\DataProvider\Business\Model\DataProvider\AbstractDataProvider;

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
     * @param \Xervice\DataProvider\Business\Model\DataProvider\AbstractDataProvider $dataProvider
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
     * @return \Xervice\DataProvider\Business\Model\DataProvider\AbstractDataProvider
     */
    public function getDataProvider(): AbstractDataProvider
    {
        return $this->dataProvider;
    }
}

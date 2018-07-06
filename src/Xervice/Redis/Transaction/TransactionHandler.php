<?php


namespace Xervice\Redis\Transaction;



use Xervice\DataProvider\DataProvider\AbstractDataProvider;

class TransactionHandler implements TransactionHandlerInterface
{
    /**
     * @var \Xervice\Redis\Transaction\TransactionCollection
     */
    private $collection;

    /**
     * TransactionHandler constructor.
     *
     * @param \Xervice\Redis\Transaction\TransactionCollection $collection
     */
    public function __construct(\Xervice\Redis\Transaction\TransactionCollection $collection)
    {
        $this->collection = $collection;
    }

    /**
     * @param \Xervice\Redis\Transaction\TransactionInterface $transaction
     */
    public function addToCollection(TransactionInterface $transaction)
    {
        $this->collection->add($transaction);
    }

    public function clearCollection()
    {
        $this->collection->clear();
    }

    /**
     * @return array
     */
    public function getTransactionArray() : array
    {
        $transactionList = [];

        foreach ($this->collection as $transaction) {
            $transactionList[$transaction->getKey()] = $transaction->getDataProvider();
        }

        return $transactionList;
    }
}
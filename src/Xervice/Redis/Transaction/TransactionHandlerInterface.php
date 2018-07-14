<?php
declare(strict_types=1);

namespace Xervice\Redis\Transaction;

interface TransactionHandlerInterface
{
    /**
     * @param \Xervice\Redis\Transaction\TransactionInterface $transaction
     */
    public function addToCollection(TransactionInterface $transaction);

    public function clearCollection();

    /**
     * @return array
     */
    public function getTransactionArray(): array;
}

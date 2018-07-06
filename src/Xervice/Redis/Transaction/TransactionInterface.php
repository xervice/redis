<?php

namespace Xervice\Redis\Transaction;

use Xervice\DataProvider\DataProvider\AbstractDataProvider;

interface TransactionInterface
{
    /**
     * @return string
     */
    public function getKey(): string;

    /**
     * @return AbstractDataProvider
     */
    public function getDataProvider(): AbstractDataProvider;
}
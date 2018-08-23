<?php
declare(strict_types=1);

namespace Xervice\Redis\Business\Model\Transaction;

use Xervice\DataProvider\Business\Model\DataProvider\AbstractDataProvider;

interface TransactionInterface
{
    /**
     * @return string
     */
    public function getKey(): string;

    /**
     * @return \Xervice\DataProvider\Business\Model\DataProvider\AbstractDataProvider
     */
    public function getDataProvider(): AbstractDataProvider;
}

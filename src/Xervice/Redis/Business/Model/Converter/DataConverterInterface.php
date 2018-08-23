<?php
declare(strict_types=1);

namespace Xervice\Redis\Business\Model\Converter;


use Xervice\DataProvider\Business\Model\DataProvider\DataProviderInterface;

interface DataConverterInterface
{
    /**
     * @param \Xervice\DataProvider\Business\Model\DataProvider\DataProviderInterface $dataProvider
     *
     * @return string
     */
    public function convertTo(DataProviderInterface $dataProvider): string;

    /**
     * @param string $data
     *
     * @return DataProviderInterface
     */
    public function convertFrom(string $data): DataProviderInterface;
}

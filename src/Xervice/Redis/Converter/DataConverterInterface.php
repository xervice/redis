<?php
declare(strict_types=1);

namespace Xervice\Redis\Converter;

use Xervice\DataProvider\DataProvider\DataProviderInterface;

interface DataConverterInterface
{
    /**
     * @param \Xervice\DataProvider\DataProvider\DataProviderInterface $dataProvider
     *
     * @return string
     */
    public function convertTo(DataProviderInterface $dataProvider): string;

    /**
     * @param string $data
     *
     * @return DataProviderInterface
     * @throws \Xervice\Redis\Converter\Exceptions\ConverterException
     */
    public function convertFrom(string $data): DataProviderInterface;
}

<?php

namespace Xervice\Redis\Converter;

use Xervice\DataProvider\DataProvider\AbstractDataProvider;

interface DataConverterInterface
{
    /**
     * @param \Xervice\DataProvider\DataProvider\AbstractDataProvider $dataProvider
     *
     * @return string
     */
    public function convertTo(AbstractDataProvider $dataProvider);

    /**
     * @param string $data
     *
     * @return AbstractDataProvider
     * @throws \Xervice\Redis\Converter\Exceptions\ConverterException
     */
    public function convertFrom(string $data): AbstractDataProvider;
}
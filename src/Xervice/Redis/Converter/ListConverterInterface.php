<?php

namespace Xervice\Redis\Converter;

interface ListConverterInterface
{
    /**
     * @param array $list
     *
     * @return array
     */
    public function convertFromList(array $list): array;

    /**
     * @param \Xervice\DataProvider\DataProvider\AbstractDataProvider[] $list
     *
     * @return array
     */
    public function convertToList(array $list): array;
}
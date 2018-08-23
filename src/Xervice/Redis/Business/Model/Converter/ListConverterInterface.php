<?php
declare(strict_types=1);

namespace Xervice\Redis\Business\Model\Converter;

interface ListConverterInterface
{
    /**
     * @param array $list
     *
     * @return array
     */
    public function convertFromList(array $list): array;

    /**
     * @param \Xervice\DataProvider\Business\Model\DataProvider\AbstractDataProvider[] $list
     *
     * @return array
     */
    public function convertToList(array $list): array;
}

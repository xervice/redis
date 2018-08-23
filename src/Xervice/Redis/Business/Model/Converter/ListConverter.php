<?php
declare(strict_types=1);


namespace Xervice\Redis\Business\Model\Converter;


use Xervice\DataProvider\Business\Model\DataProvider\DataProviderInterface;

class ListConverter implements ListConverterInterface
{
    /**
     * @var \Xervice\Redis\Business\Model\Converter\DataConverterInterface
     */
    private $dataConverter;

    /**
     * ListConverter constructor.
     *
     * @param \Xervice\Redis\Business\Model\Converter\DataConverterInterface $dataConverter
     */
    public function __construct(DataConverterInterface $dataConverter)
    {
        $this->dataConverter = $dataConverter;
    }

    /**
     * @param array $list
     *
     * @return array
     */
    public function convertFromList(array $list) : array
    {
        return array_map(
            [
                $this,
                'convertFrom'
            ],
            $list
        );
    }

    /**
     * @param \Xervice\DataProvider\Business\Model\DataProvider\DataProviderInterface[] $list
     *
     * @return array
     */
    public function convertToList(array $list) : array
    {
        return array_map(
            [
                $this,
                'convertTo'
            ],
            $list
        );
    }

    /**
     * @param \Xervice\DataProvider\Business\Model\DataProvider\DataProviderInterface $dataProvider
     *
     * @return string
     */
    private function convertTo(DataProviderInterface $dataProvider): string
    {
        return $this->dataConverter->convertTo($dataProvider);
    }

    /**
     * @param string $element
     *
     * @return \Xervice\DataProvider\Business\Model\DataProvider\DataProviderInterface
     */
    private function convertFrom(string $element) : DataProviderInterface
    {
        return $this->dataConverter->convertFrom($element);
    }
}

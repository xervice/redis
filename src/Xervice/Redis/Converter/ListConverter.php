<?php


namespace Xervice\Redis\Converter;


use Xervice\DataProvider\DataProvider\AbstractDataProvider;

class ListConverter implements ListConverterInterface
{
    /**
     * @var \Xervice\Redis\Converter\DataConverterInterface
     */
    private $dataConverter;

    /**
     * ListConverter constructor.
     *
     * @param \Xervice\Redis\Converter\DataConverterInterface $dataConverter
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
     * @param \Xervice\DataProvider\DataProvider\AbstractDataProvider[] $list
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
     * @param \Xervice\DataProvider\DataProvider\AbstractDataProvider $dataProvider
     *
     * @return string
     */
    private function convertTo(AbstractDataProvider $dataProvider)
    {
        return $this->dataConverter->convertTo($dataProvider);
    }

    /**
     * @param string $element
     *
     * @return \Xervice\DataProvider\DataProvider\AbstractDataProvider
     * @throws \Xervice\Redis\Converter\Exceptions\ConverterException
     */
    private function convertFrom(string $element) : AbstractDataProvider
    {
        return $this->dataConverter->convertFrom($element);
    }
}
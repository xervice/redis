<?php


namespace Xervice\Redis\Converter;


use Xervice\DataProvider\DataProvider\AbstractDataProvider;
use Xervice\Redis\Converter\Exceptions\ConverterException;

class DataConverter implements DataConverterInterface
{
    /**
     * @param \Xervice\DataProvider\DataProvider\AbstractDataProvider $dataProvider
     *
     * @return string
     */
    public function convertTo(AbstractDataProvider $dataProvider)
    {
        return json_encode([
            'class' => get_class($dataProvider),
            'data' => $dataProvider->toArray()
        ]);
    }

    /**
     * @param string $data
     *
     * @return AbstractDataProvider
     * @throws \Xervice\Redis\Converter\Exceptions\ConverterException
     */
    public function convertFrom(string $data): AbstractDataProvider
    {
        $data = json_decode($data, true);

        if (!isset($data['class']) || !isset($data['data'])) {
            throw new ConverterException('Data have wrong format to convert (No class or data field)');
        }

        $provider = $data['class'];

        $provider = new $provider();
        $provider->fromArray($data['data']);

        return $provider;
    }
}
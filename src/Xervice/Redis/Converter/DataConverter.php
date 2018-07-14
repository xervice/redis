<?php
declare(strict_types=1);


namespace Xervice\Redis\Converter;


use Xervice\DataProvider\DataProvider\DataProviderInterface;
use Xervice\Redis\Converter\Exceptions\ConverterException;

class DataConverter implements DataConverterInterface
{
    /**
     * @param \Xervice\DataProvider\DataProvider\DataProviderInterface $dataProvider
     *
     * @return string
     */
    public function convertTo(DataProviderInterface $dataProvider): string
    {
        return json_encode(
            [
                'class' => \get_class($dataProvider),
                'data'  => $dataProvider->toArray()
            ]
        );
    }

    /**
     * @param string $data
     *
     * @return \Xervice\DataProvider\DataProvider\DataProviderInterface
     * @throws \Xervice\Redis\Converter\Exceptions\ConverterException
     */
    public function convertFrom(string $data): DataProviderInterface
    {
        $data = json_decode($data, true);

        if (!isset($data['data'], $data['class'])) {
            throw new ConverterException('Data have wrong format to convert (No class or data field)');
        }

        $provider = $data['class'];

        $provider = $this->createDataProvider($provider);
        $provider->fromArray($data['data']);

        return $provider;
    }

    /**
     * @param $provider
     *
     * @return \Xervice\DataProvider\DataProvider\DataProviderInterface
     */
    private function createDataProvider($provider): DataProviderInterface
    {
        $provider = new $provider();
        return $provider;
    }
}

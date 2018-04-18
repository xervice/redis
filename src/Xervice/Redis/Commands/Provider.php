<?php


namespace Xervice\Redis\Commands;

use Predis\Client;

class Provider implements ProviderInterface
{
    /**
     * @var \Xervice\Redis\Commands\Collection
     */
    private $commandCollection;

    /**
     * @var \Predis\Client
     */
    private $client;

    /**
     * Provider constructor.
     *
     * @param \Xervice\Redis\Commands\Collection $commandCollection
     * @param \Predis\Client $client
     */
    public function __construct(Collection $commandCollection, Client $client)
    {
        $this->commandCollection = $commandCollection;
        $this->client = $client;
    }


    public function provideCommands()
    {
        foreach ($this->commandCollection as $commandProvider) {
            $commandProvider->register($this->client);
        }
    }
}
<?php
declare(strict_types=1);


namespace Xervice\Redis\Business\Model\Commands;

use Predis\Client;

class Provider implements ProviderInterface
{
    /**
     * @var \Xervice\Redis\Business\Model\Commands\Collection
     */
    private $commandCollection;

    /**
     * @var \Predis\Client
     */
    private $client;

    /**
     * Provider constructor.
     *
     * @param \Xervice\Redis\Business\Model\Commands\Collection $commandCollection
     * @param \Predis\Client $client
     */
    public function __construct(Collection $commandCollection, Client $client)
    {
        $this->commandCollection = $commandCollection;
        $this->client = $client;
    }


    public function provideCommands(): void
    {
        foreach ($this->commandCollection as $commandProvider) {
            $commandProvider->register($this->client);
        }
    }
}

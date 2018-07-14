<?php
declare(strict_types=1);


namespace Xervice\Redis\Commands;


use Predis\Client;

interface CommandProviderInterface
{
    /**
     * @param \Predis\Client $client
     */
    public function register(Client $client): void;
}

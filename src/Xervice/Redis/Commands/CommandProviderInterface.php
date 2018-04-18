<?php


namespace Xervice\Redis\Commands;


use Predis\Client;

interface CommandProviderInterface
{
    /**
     * @param \Predis\Client $client
     */
    public function register(Client $client): void;
}
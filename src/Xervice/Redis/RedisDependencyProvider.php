<?php


namespace Xervice\Redis;


use Xervice\Core\Dependency\DependencyProviderInterface;
use Xervice\Core\Dependency\Provider\AbstractProvider;
use Xervice\Redis\Commands\Collection;

/**
 * @method \Xervice\Core\Locator\Locator getLocator()
 */
class RedisDependencyProvider extends AbstractProvider
{
    const REDIS_COMMAND_COLLECTION = 'redis.command.collection';

    /**
     * @param \Xervice\Core\Dependency\DependencyProviderInterface $container
     */
    public function handleDependencies(DependencyProviderInterface $container)
    {
        $container[self::REDIS_COMMAND_COLLECTION] = function (DependencyProviderInterface $container) {
            return new Collection(
                $this->getCommands()
            );
        };
    }

    /**
     * @return \Xervice\Redis\Commands\CommandProviderInterface[]
     */
    protected function getCommands(): array
    {
        return [];
    }
}
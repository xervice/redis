<?php
declare(strict_types=1);


namespace Xervice\Redis;


use Xervice\Core\Dependency\DependencyProviderInterface;
use Xervice\Core\Dependency\Provider\AbstractProvider;
use Xervice\Redis\Commands\Collection;

/**
 * @method \Xervice\Core\Locator\Locator getLocator()
 */
class RedisDependencyProvider extends AbstractProvider
{
    public const REDIS_COMMAND_COLLECTION = 'redis.command.collection';

    /**
     * @param \Xervice\Core\Dependency\DependencyProviderInterface $dependencyProvider
     */
    public function handleDependencies(DependencyProviderInterface $dependencyProvider): void
    {
        $dependencyProvider[self::REDIS_COMMAND_COLLECTION] = function () {
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

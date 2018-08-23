<?php
declare(strict_types=1);


namespace Xervice\Redis;


use Xervice\Core\Business\Model\Dependency\DependencyContainerInterface;
use Xervice\Core\Business\Model\Dependency\Provider\AbstractDependencyProvider;
use Xervice\Redis\Business\Model\Commands\Collection;

class RedisDependencyProvider extends AbstractDependencyProvider
{
    public const REDIS_COMMAND_COLLECTION = 'redis.command.collection';

    /**
     * @param \Xervice\Core\Business\Model\Dependency\DependencyContainerInterface $container
     *
     * @return \Xervice\Core\Business\Model\Dependency\DependencyContainerInterface
     */
    public function handleDependencies(DependencyContainerInterface $container): DependencyContainerInterface
    {
        $container = $this->addCommandCollection($container);

        return $container;
    }

    /**
     * @return \Xervice\Redis\Business\Model\Commands\CommandProviderInterface[]
     */
    protected function getCommands(): array
    {
        return [];
    }

    /**
     * @param \Xervice\Core\Business\Model\Dependency\DependencyContainerInterface $container
     *
     * @return \Xervice\Core\Business\Model\Dependency\DependencyContainerInterface
     */
    protected function addCommandCollection(DependencyContainerInterface $container
    ): \Xervice\Core\Business\Model\Dependency\DependencyContainerInterface {
        $container[self::REDIS_COMMAND_COLLECTION] = function () {
            return new Collection(
                $this->getCommands()
            );
        };
        return $container;
}
}

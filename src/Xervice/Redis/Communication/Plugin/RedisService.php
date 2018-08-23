<?php
declare(strict_types=1);

namespace Xervice\Redis\Communication\Plugin;

use Xervice\Core\Plugin\AbstractCommunicationPlugin;
use Xervice\Kernel\Business\Model\Service\ServiceProviderInterface;
use Xervice\Kernel\Business\Plugin\BootInterface;

/**
 * @method \Xervice\Redis\Business\RedisFacade getFacade()
 */
class RedisService extends AbstractCommunicationPlugin implements BootInterface
{
    /**
     * @param \Xervice\Kernel\Business\Model\Service\ServiceProviderInterface $serviceProvider
     *
     */
    public function boot(ServiceProviderInterface $serviceProvider): void
    {
        $this->getFacade()->init();
    }
}

<?php
declare(strict_types=1);

namespace Xervice\Redis\Communication\Plugin;

use DataProvider\RedisSessionDataProvider;
use Symfony\Component\HttpFoundation\Session\Storage\Handler\AbstractSessionHandler;
use Xervice\Core\Business\Model\Locator\Dynamic\Business\DynamicBusinessLocator;
use Xervice\Redis\Business\Exception\RedisException;

/**
 * @method \Xervice\Session\Business\SessionFacade getFacade()
 */
class RedisSessionHandler extends AbstractSessionHandler
{
    use DynamicBusinessLocator;

    /**
     * @var string
     */
    private $prefix;

    /**
     * @var int
     */
    private $ttl;

    /**
     * @var RedisSessionDataProvider
     */
    private $sessionDataProvider;

    /**
     * RedisSessionHandler constructor.
     *
     * @param \Xervice\Redis\Business\RedisFacade $facade
     * @param array $options
     */
    public function __construct(array $options = [])
    {
        $this->prefix = $options['prefix'] ?? 'session:xervice.';
        $this->ttl = $options['ttl'] ?? 86400;
        $this->sessionDataProvider = new RedisSessionDataProvider();
    }

    /**
     * @param string $sessionId
     *
     * @return string
     */
    protected function doRead($sessionId): string
    {
        try {
            $this->sessionDataProvider = $this->getFacade()->get($this->prefix . $sessionId);
        } catch (RedisException $exception) {
            $this->sessionDataProvider->setData('');
        }

        return $this->sessionDataProvider->getData();
    }

    /**
     * @param string $sessionId
     * @param string $data
     *
     * @return bool
     */
    protected function doWrite($sessionId, $data): bool
    {
        $this->sessionDataProvider->setData($data);
        $this->getFacade()->setEx($this->prefix . $sessionId, $this->sessionDataProvider, 'ex', $this->ttl);

        return true;
    }

    /**
     * @param string $sessionId
     *
     * @return bool
     */
    protected function doDestroy($sessionId): bool
    {
        $this->getFacade()->delete($this->prefix . $sessionId);

        return true;
    }

    /**
     * @return bool
     */
    public function close(): bool
    {
        return true;
    }

    /**
     * @param int $maxlifetime
     *
     * @return bool
     */
    public function gc($maxlifetime): bool
    {
        return true;
    }

    /**
     * @param string $session_id
     * @param string $session_data
     *
     * @return bool
     */
    public function updateTimestamp($session_id, $session_data): bool
    {
        $this->getFacade()->expire($this->prefix . $session_id, $this->ttl);

        return true;
    }
}

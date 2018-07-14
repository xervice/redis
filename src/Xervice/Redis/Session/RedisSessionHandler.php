<?php
declare(strict_types=1);


namespace Xervice\Redis\Session;


use DataProvider\RedisSessionDataProvider;
use Symfony\Component\HttpFoundation\Session\Storage\Handler\AbstractSessionHandler;
use Xervice\Redis\Exception\RedisException;
use Xervice\Redis\RedisClient;

class RedisSessionHandler extends AbstractSessionHandler
{
    /**
     * @var \Xervice\Redis\RedisClient
     */
    private $client;

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
     * @param \Xervice\Redis\RedisClient $client
     * @param string $prefix
     */
    public function __construct(RedisClient $client, array $options = [])
    {
        $this->client = $client;
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
            $this->sessionDataProvider = $this->client->get($this->prefix . $sessionId);
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
        $this->client->setEx($this->prefix . $sessionId, $this->sessionDataProvider, 'ex', $this->ttl);

        return true;
    }

    /**
     * @param string $sessionId
     *
     * @return bool
     */
    protected function doDestroy($sessionId): bool
    {
        $this->client->delete($this->prefix . $sessionId);

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
        $this->client->expire($this->prefix . $session_id, $this->ttl);

        return true;
    }
}

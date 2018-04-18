<?php


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
        $this->prefix = isset($options['prefix']) ? $options['prefix'] : 'redis.session.';
        $this->ttl = isset($options['ttl']) ? $options['ttl'] : 86400;
        $this->sessionDataProvider = new RedisSessionDataProvider();
    }

    /**
     * @param string $sessionId
     *
     * @return string
     * @throws \Xervice\Config\Exception\ConfigNotFound
     */
    protected function doRead($sessionId)
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
     * @throws \Xervice\Config\Exception\ConfigNotFound
     */
    protected function doWrite($sessionId, $data)
    {
        $this->sessionDataProvider->setData($data);
        $this->client->setEx($this->prefix . $sessionId, $this->sessionDataProvider, 'ex', $this->ttl);

        return true;
    }

    /**
     * @param string $sessionId
     *
     * @return bool
     * @throws \Xervice\Config\Exception\ConfigNotFound
     */
    protected function doDestroy($sessionId)
    {
        $this->client->delete($this->prefix . $sessionId);

        return true;
    }

    /**
     * @return bool
     */
    public function close()
    {
        return true;
    }

    /**
     * @param int $maxlifetime
     *
     * @return bool
     */
    public function gc($maxlifetime)
    {
        return true;
    }

    /**
     * @param string $session_id
     * @param string $session_data
     *
     * @return bool
     * @throws \Xervice\Config\Exception\ConfigNotFound
     */
    public function updateTimestamp($session_id, $session_data)
    {
        $this->client->expire($this->prefix . $session_id, $this->ttl);

        return true;
    }
}
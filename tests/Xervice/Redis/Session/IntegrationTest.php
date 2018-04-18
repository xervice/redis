<?php
namespace XerviceTest\Redis\Session;

use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\NativeSessionStorage;
use Xervice\Core\Locator\Dynamic\DynamicLocator;
use Xervice\Redis\Session\RedisSessionHandler;

/**
 * @method \Xervice\Redis\RedisFactory getFactory()
 * @method \Xervice\Redis\RedisFacade getFacade()
 */
class IntegrationTest extends \Codeception\Test\Unit
{
    use DynamicLocator;

    /**
     * @var \XerviceTest\XerviceTester
     */
    protected $tester;
    
    protected function _before()
    {
        $this->getFacade()->init();
    }

    protected function _after()
    {
        $this->getFacade()->flushAll();
    }

    /**
     * @group Xervice
     * @group Redis
     * @group Session
     * @group Integration
     *
     * @throws \Core\Locator\Dynamic\ServiceNotParseable
     */
    public function testRedisSession()
    {
        $storage = new NativeSessionStorage([], new RedisSessionHandler($this->getClient()));
        $session = new Session($storage);

        $session->set('test', 'hallo');

        $this->assertEquals(
            'hallo',
            $session->get('test')
        );
    }
}
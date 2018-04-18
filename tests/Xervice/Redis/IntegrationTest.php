<?php
namespace XerviceTest\Redis;

use DataProvider\TestKeyValueDataProvider;
use Xervice\Core\Locator\Dynamic\DynamicLocator;

/**
 * @method \Xervice\Redis\RedisFacade getFacade()
 * @method \Xervice\Redis\RedisClient getClient()
 * @method \Xervice\Redis\RedisFactory getFactory()
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
     * @group Integration
     *
     * @throws \Core\Locator\Dynamic\ServiceNotParseable
     * @throws \Xervice\Config\Exception\ConfigNotFound
     */
    public function testSetValue()
    {
        $provider = new TestKeyValueDataProvider();
        $provider->setKey('test');
        $provider->setDescription('desc');
        $provider->setValue('val');

        $this->getClient()->set('redis.test', $provider);
    }

    /**
     * @group Xervice
     * @group Redis
     * @group Integration
     *
     * @expectedException \Xervice\Redis\Exception\RedisException
     * @expectedExceptionMessage No value found for key redis.test
     */
    public function testGetValueWithException()
    {
        $provider = $this->getClient()->get('redis.test');
    }

    /**
     * @group Xervice
     * @group Redis
     * @group Integration
     *
     * @throws \Core\Locator\Dynamic\ServiceNotParseable
     * @throws \Xervice\Config\Exception\ConfigNotFound
     */
    public function testGetValue()
    {
        $this->testSetValue();

        $provider = $this->getClient()->get('redis.test');
        $this->assertEquals(
            'test',
            $provider->getKey()
        );

        $this->assertEquals(
            'desc',
            $provider->getDescription()
        );

        $this->assertEquals(
            'val',
            $provider->getValue()
        );
    }

    /**
     * @group Xervice
     * @group Redis
     * @group Integration
     *
     * @expectedException \Xervice\Redis\Exception\RedisException
     * @expectedExceptionMessage No value found for key redis.test
     *
     * @throws \Core\Locator\Dynamic\ServiceNotParseable
     * @throws \Xervice\Config\Exception\ConfigNotFound
     */
    public function testDelete()
    {
        $this->testSetValue();

        $this->getClient()->delete('redis.test');
        $this->getClient()->get('redis.test');
    }
}
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
        $provider = $this->getTestProvider();

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

    /**
     * @throws \Core\Locator\Dynamic\ServiceNotParseable
     * @throws \Xervice\Config\Exception\ConfigNotFound
     */
    public function testMultiSet()
    {
        $list = [
            'redis.test1' => $provider = $this->getTestProvider('test1', 'desc1', 'val1'),
            'redis.test2' => $provider = $this->getTestProvider('test2', 'desc2', 'val2'),
            'redis.test3' => $provider = $this->getTestProvider('test3', 'desc3', 'val3')
        ];


        $this->getClient()->mset($list);
    }

    /**
     * @throws \Core\Locator\Dynamic\ServiceNotParseable
     * @throws \Xervice\Config\Exception\ConfigNotFound
     */
    public function testMultiGet()
    {
        $keys = [
            'redis.test1',
            'redis.test2',
            'redis.test3'
        ];

        $this->testMultiSet();

        $myResult = $this->getClient()->mget($keys);

        $this->assertCount(
            3,
            $myResult
        );

        $this->assertEquals(
            'test1',
            $myResult[0]->getKey()
        );

        $this->assertEquals(
            'test2',
            $myResult[1]->getKey()
        );

        $this->assertEquals(
            'test3',
            $myResult[2]->getKey()
        );
    }

    /**
     * @param string $key
     * @param string $desc
     * @param string $val
     *
     * @return \DataProvider\TestKeyValueDataProvider
     */
    private function getTestProvider(string $key = 'test', string $desc = 'desc', string $val = 'val')
    {
        $provider = new TestKeyValueDataProvider();
        $provider->setKey($key);
        $provider->setDescription($desc);
        $provider->setValue($val);

        return $provider;
    }
}
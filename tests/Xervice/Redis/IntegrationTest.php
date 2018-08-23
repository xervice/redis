<?php
namespace XerviceTest\Redis;

use DataProvider\TestKeyValueDataProvider;
use Xervice\Config\Business\XerviceConfig;
use Xervice\Core\Business\Model\Locator\Dynamic\Business\DynamicBusinessLocator;
use Xervice\Core\Business\Model\Locator\Locator;
use Xervice\DataProvider\Business\DataProviderFacade;
use Xervice\DataProvider\DataProviderConfig;

/**
 * @method \Xervice\Redis\Business\RedisFacade getFacade()
 * @method \Xervice\Redis\Business\RedisBusinessFactory getFactory()
 */
class IntegrationTest extends \Codeception\Test\Unit
{
    use DynamicBusinessLocator;

    /**
     * @var \XerviceTest\XerviceTester
     */
    protected $tester;

    protected function _before()
    {
        XerviceConfig::set(DataProviderConfig::FILE_PATTERN, '*.dataprovider.xml');
        $this->getDataProviderFacade()->generateDataProvider();
        XerviceConfig::set(DataProviderConfig::FILE_PATTERN, '*.testprovider.xml');
        $this->getDataProviderFacade()->generateDataProvider();
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
     */
    public function testSetValue()
    {
        $provider = $this->getTestProvider();

        $this->assertFalse($this->getFacade()->exists('redis.test'));
        $this->getFacade()->set('redis.test', $provider);
    }

    /**
     * @group Xervice
     * @group Redis
     * @group Integration
     *
     * @expectedException \Xervice\Redis\Business\Exception\RedisException
     * @expectedExceptionMessage No value found for key redis.test
     */
    public function testGetValueWithException()
    {
        $provider = $this->getFacade()->get('redis.test');
    }

    /**
     * @group Xervice
     * @group Redis
     * @group Integration
     */
    public function testGetValue()
    {
        $this->testSetValue();

        $this->assertTrue($this->getFacade()->exists('redis.test'));

        $provider = $this->getFacade()->get('redis.test');
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
     * @expectedException \Xervice\Redis\Business\Exception\RedisException
     * @expectedExceptionMessage No value found for key redis.test
     */
    public function testDelete()
    {
        $this->testSetValue();

        $this->getFacade()->delete('redis.test');
        $this->getFacade()->get('redis.test');
    }

    public function testMultiSet()
    {
        $list = [
            'redis.test1' => $provider = $this->getTestProvider('test1', 'desc1', 'val1'),
            'redis.test2' => $provider = $this->getTestProvider('test2', 'desc2', 'val2'),
            'redis.test3' => $provider = $this->getTestProvider('test3', 'desc3', 'val3')
        ];


        $this->getFacade()->mset($list);
    }

    public function testMultiGet()
    {
        $keys = [
            'redis.test1',
            'redis.test2',
            'redis.test3'
        ];

        $this->testMultiSet();

        $myResult = $this->getFacade()->mget($keys);

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

    public function testTransactions()
    {
        $this->getFacade()->startTransaction();
        $this->getFacade()->addTransaction(
            'redis.trans.test1',
            $this->getTestProvider('test1', 'desc1', 'val1')
        );
        $this->getFacade()->addTransaction(
            'redis.trans.test2',
            $this->getTestProvider('test2', 'desc2', 'val2')
        );
        $this->getFacade()->addTransaction(
            'redis.trans.test3',
            $this->getTestProvider('test3', 'desc3', 'val3')
        );

        $this->assertFalse($this->getFacade()->exists('redis.trans.test1'));
        $this->assertFalse($this->getFacade()->exists('redis.trans.test2'));
        $this->assertFalse($this->getFacade()->exists('redis.trans.test3'));

        $this->getFacade()->persistTransaction();

        $this->assertTrue($this->getFacade()->exists('redis.trans.test1'));
        $this->assertTrue($this->getFacade()->exists('redis.trans.test2'));
        $this->assertTrue($this->getFacade()->exists('redis.trans.test3'));
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

    /**
     * @return \Xervice\DataProvider\Business\DataProviderFacade
     */
    private function getDataProviderFacade(): DataProviderFacade
    {
        return Locator::getInstance()->dataProvider()->facade();
    }

}
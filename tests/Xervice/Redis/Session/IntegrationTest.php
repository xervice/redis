<?php
namespace XerviceTest\Redis\Session;

use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\NativeSessionStorage;
use Xervice\Config\Business\XerviceConfig;
use Xervice\Core\Business\Model\Locator\Dynamic\Business\DynamicBusinessLocator;
use Xervice\Core\Business\Model\Locator\Locator;
use Xervice\DataProvider\Business\DataProviderFacade;
use Xervice\DataProvider\DataProviderConfig;
use Xervice\Redis\Business\Model\Session\RedisSessionHandler;

/**
 * @method \Xervice\Redis\Business\RedisBusinessFactory getFactory()
 * @method \Xervice\Redis\Business\RedisFacade getFacade()
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
     * @group Session
     * @group Integration
     */
    public function testRedisSession()
    {
        $storage = new NativeSessionStorage([], new RedisSessionHandler($this->getFacade()));
        $session = new Session($storage);

        $session->set('test', 'hallo');

        $this->assertEquals(
            'hallo',
            $session->get('test')
        );
    }

    /**
     * @return \Xervice\DataProvider\Business\DataProviderFacade
     */
    private function getDataProviderFacade(): DataProviderFacade
    {
        return Locator::getInstance()->dataProvider()->facade();
    }
}
<?php
namespace Jh\MagentoComposerInstallerTest\InstallStrategy;

use Jh\MagentoComposerInstaller\InstallStrategy\None;
use Jh\MagentoComposerInstaller\Map\Map;
use PHPUnit_Framework_TestCase;

/**
 * Class NoneTest
 * @package Jh\MagentoComposerInstaller\Magento\InstallStrategy
 */
class NoneTest extends PHPUnit_Framework_TestCase
{
    public function testResolveReturnsEmptyArray()
    {
        $none = new None;
        $this->assertInstanceOf('Jh\MagentoComposerInstaller\InstallStrategy\InstallStrategyInterface', $none);
        $this->assertSame([], $none->resolve('some/source', 'some/destination', '/root', '/root'));
    }

    public function testCreateDoesNothing()
    {
        $none = new None;
        $map = new Map('some/source', 'some/destination', '/root', '/root');
        $this->assertNull($none->create($map, false));
    }
}

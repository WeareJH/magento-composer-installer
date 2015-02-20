<?php

namespace Jh\MagentoComposerInstallerTest\Event;

use Jh\MagentoComposerInstaller\Event\PackageUnInstallEvent;
use Jh\MagentoComposerInstaller\InstalledPackage;
use Jh\MagentoComposerInstaller\Map\Map;
use Jh\MagentoComposerInstaller\Map\MapCollection;
use PHPUnit_Framework_TestCase;

/**
 * Class PackageUnInstallEventTest
 * @package Jh\MagentoComposerInstaller\Magento\Event
 * @author Aydin Hassan <aydin@hotmail.co.uk>
 */
class PackageUnInstallEventTest extends PHPUnit_Framework_TestCase
{
    public function testGetters()
    {

        $maps = new MapCollection([
            new Map('file1', 'file1', '/', '/'),
            new Map('file2', 'file2', '/', '/'),
        ]);
        $package    = new InstalledPackage('some/package', '1.0.0', $maps);
        $event      = new PackageUnInstallEvent('package-uninstall-event', $package);

        $this->assertSame($package, $event->getPackage());
        $this->assertEquals($maps, $event->getMappings());
    }
}

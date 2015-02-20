<?php

namespace Jh\MagentoComposerInstallerTest\Event;

use Composer\Package\Package;
use Jh\MagentoComposerInstaller\Event\PackageDeployEvent;
use Jh\MagentoComposerInstaller\Event\PackagePostInstallEvent;
use Jh\MagentoComposerInstaller\InstalledPackage;
use Jh\MagentoComposerInstaller\Map\Map;
use Jh\MagentoComposerInstaller\Map\MapCollection;
use PHPUnit_Framework_TestCase;

/**
 * Class PackagePostInstallEventTest
 * @package Jh\MagentoComposerInstaller\Magento\Event
 * @author Aydin Hassan <aydin@hotmail.co.uk>
 */
class PackagePostInstallEventTest extends PHPUnit_Framework_TestCase
{
    public function testGetters()
    {
        $map        = new Map('source', 'destination', '/tmp/', '/tmp/');
        $collection = new MapCollection([$map]);
        $installedP = new InstalledPackage('some/package', '1.0.0', $collection);

        $package    = new Package('some/package', '1.0.0', 'some/package');
        $event      = new PackagePostInstallEvent($package, $installedP);

        $this->assertEquals('package-post-install', $event->getName());
        $this->assertSame($package, $event->getPackage());
        $this->assertEquals($collection, $event->getMappings());
    }
}

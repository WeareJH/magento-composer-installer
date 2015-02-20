<?php

namespace Jh\MagentoComposerInstallerTest\Event;

use Composer\Package\Package;
use Jh\MagentoComposerInstaller\Event\PackageDeployEvent;
use Jh\MagentoComposerInstaller\Event\PackagePreInstallEvent;
use PHPUnit_Framework_TestCase;

/**
 * Class PackagePreInstallEventTest
 * @package Jh\MagentoComposerInstaller\Magento\Event
 * @author Aydin Hassan <aydin@hotmail.co.uk>
 */
class PackagePreInstallEventTest extends PHPUnit_Framework_TestCase
{
    public function testGetters()
    {
        $package    = new Package('some/package', '1.0.0', 'some/package');
        $event      = new PackagePreInstallEvent($package);

        $this->assertEquals('package-pre-install', $event->getName());
        $this->assertSame($package, $event->getPackage());
    }
}

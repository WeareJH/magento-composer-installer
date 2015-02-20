<?php

namespace Jh\MagentoComposerInstallerTest\Event;

use ArrayObject;
use Composer\Package\Package;
use Jh\MagentoComposerInstaller\Event\InstallEvent;
use Jh\MagentoComposerInstaller\Event\PackageDeployEvent;
use PHPUnit_Framework_TestCase;

/**
 * Class InstallEventTest
 * @package Jh\MagentoComposerInstaller\Magento\Event
 * @author Aydin Hassan <aydin@hotmail.co.uk>
 */
class InstallEventTest extends PHPUnit_Framework_TestCase
{
    public function testGetters()
    {
        $packages   = new ArrayObject([new Package('some/package', '1.0.0', 'some/package')]);
        $event      = new InstallEvent('pre-install', $packages);

        $this->assertEquals('pre-install', $event->getName());
        $this->assertSame($packages, $event->getPackages());
    }
}

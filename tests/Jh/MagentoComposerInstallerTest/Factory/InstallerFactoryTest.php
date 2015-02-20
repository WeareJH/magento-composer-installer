<?php

namespace Jh\MagentoComposerInstallerTest\Factory;

use Jh\MagentoComposerInstaller\Event\EventManager;
use Jh\MagentoComposerInstaller\Factory\InstallerFactory;
use Jh\MagentoComposerInstaller\ProjectConfig;
use PHPUnit_Framework_TestCase;

/**
 * Class InstallerFactoryTest
 * @package Jh\MagentoComposerInstaller\Magento\Factory
 * @author  Aydin Hassan <aydin@hotmail.co.uk>
 */
class InstallerFactoryTest extends PHPUnit_Framework_TestCase
{
    public function testInstanceIsReturned()
    {
        $factory = new InstallerFactory;
        $instance = $factory->make(new ProjectConfig([], []), new EventManager);

        $this->assertInstanceOf('Jh\MagentoComposerInstaller\Installer\Installer', $instance);
    }
}

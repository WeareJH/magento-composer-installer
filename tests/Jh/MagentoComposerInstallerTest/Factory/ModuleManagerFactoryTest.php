<?php

namespace Jh\MagentoComposerInstallerTest\Factory;

use Composer\IO\ConsoleIO;
use Composer\Package\Package;
use Jh\MagentoComposerInstaller\Event\EventManager;
use Jh\MagentoComposerInstaller\Event\PackagePreInstallEvent;
use Jh\MagentoComposerInstaller\Factory\ModuleManagerFactory;
use Jh\MagentoComposerInstaller\ProjectConfig;
use PHPUnit_Framework_TestCase;
use Symfony\Component\Console\Helper\HelperSet;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Console\Input\ArrayInput;

/**
 * Class ModuleManagerFactoryTest
 * @package Jh\MagentoComposerInstaller\Magento\Factory
 * @author  Aydin Hassan <aydin@hotmail.co.uk>
 */
class ModuleManagerFactoryTest extends PHPUnit_Framework_TestCase
{
    public function testFactoryReturnsInstance()
    {
        $factory        = new ModuleManagerFactory;
        $config         = new ProjectConfig([], ['vendor-dir' => 'vendor']);
        $eventManager   = $this->getMock('Jh\MagentoComposerInstaller\Event\EventManager');
        $io             = new ConsoleIO(new ArrayInput([]), new ConsoleOutput(), new HelperSet());

        //gitignore add
        $eventManager
            ->expects($this->at(0))
            ->method('listen')
            ->with('package-post-install', $this->isType('array'));

        //git ignore 3
        $eventManager
            ->expects($this->at(1))
            ->method('listen')
            ->with('package-post-uninstall', $this->isType('array'));

        $eventManager
            ->expects($this->at(2))
            ->method('listen')
            ->with(
                'pre-install',
                $this->isInstanceOf('Jh\MagentoComposerInstaller\Listener\CheckAndCreateMagentoRootDirListener')
            );

        $eventManager
            ->expects($this->at(3))
            ->method('listen')
            ->with(
                'pre-install',
                $this->isInstanceOf('Jh\MagentoComposerInstaller\Listener\PackagePrioritySortListener')
            );

        $instance = $factory->make($config, $eventManager, $io);
        $this->assertInstanceOf('Jh\MagentoComposerInstaller\ModuleManager', $instance);
    }

    public function testDebugPrinterIsAddedIfDebugMode()
    {
        $factory        = new ModuleManagerFactory;
        $config         = new ProjectConfig([], ['vendor-dir' => 'vendor']);
        $eventManager   = $this->getMock('Jh\MagentoComposerInstaller\Event\EventManager');
        $io             = new ConsoleIO(
            new ArrayInput([]),
            new ConsoleOutput(ConsoleOutput::VERBOSITY_DEBUG),
            new HelperSet()
        );

        //gitignore add
        $eventManager
            ->expects($this->at(0))
            ->method('listen')
            ->with('package-post-install', $this->isType('array'));

        //git ignore 3
        $eventManager
            ->expects($this->at(1))
            ->method('listen')
            ->with('package-post-uninstall', $this->isType('array'));

        $eventManager
            ->expects($this->at(2))
            ->method('listen')
            ->with('package-pre-install', $this->isInstanceOf('Closure'));

        $eventManager
            ->expects($this->at(3))
            ->method('listen')
            ->with(
                'pre-install',
                $this->isInstanceOf('Jh\MagentoComposerInstaller\Listener\CheckAndCreateMagentoRootDirListener')
            );

        $eventManager
            ->expects($this->at(4))
            ->method('listen')
            ->with(
                'pre-install',
                $this->isInstanceOf('Jh\MagentoComposerInstaller\Listener\PackagePrioritySortListener')
            );

        $factory->make($config, $eventManager, $io);
    }

    public function testGitIgnoreListenerIsNotAddedIfDisableGitIgnoreConfigPresent()
    {
        $factory        = new ModuleManagerFactory;
        $config         = new ProjectConfig(['disable-gitignore-manage' => true], ['vendor-dir' => 'vendor']);
        $eventManager   = $this->getMock('Jh\MagentoComposerInstaller\Event\EventManager');
        $io             = new ConsoleIO(new ArrayInput([]), new ConsoleOutput(), new HelperSet());

        $eventManager
            ->expects($this->at(0))
            ->method('listen')
            ->with(
                'pre-install',
                $this->isInstanceOf('Jh\MagentoComposerInstaller\Listener\CheckAndCreateMagentoRootDirListener')
            );

        $eventManager
            ->expects($this->at(1))
            ->method('listen')
            ->with(
                'pre-install',
                $this->isInstanceOf('Jh\MagentoComposerInstaller\Listener\PackagePrioritySortListener')
            );

        $factory->make($config, $eventManager, $io);
    }

    public function testDebugListenerCallback()
    {
        $factory        = new ModuleManagerFactory;
        $config         = new ProjectConfig(['auto-append-gitignore' => true], ['vendor-dir' => 'vendor']);
        $eventManager   = new EventManager;
        $io             = $this->getMock('Composer\IO\IOInterface');

        $io->expects($this->any())
            ->method('isDebug')
            ->will($this->returnValue(true));

        $factory->make($config, $eventManager, $io);

        $io->expects($this->once())
            ->method('write')
            ->with('Start magento deploy for some/package');

        $eventManager->dispatch(new PackagePreInstallEvent(new Package('some/package', '1.0.0', 'some/package')));
    }
}

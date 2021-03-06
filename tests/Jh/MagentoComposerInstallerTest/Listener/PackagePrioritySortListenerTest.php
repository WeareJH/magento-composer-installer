<?php

namespace Jh\MagentoComposerInstallerTest\Listener;

use ArrayObject;
use Composer\Package\Package;
use Jh\MagentoComposerInstaller\Event\InstallEvent;
use Jh\MagentoComposerInstaller\Factory\InstallStrategyFactory;
use Jh\MagentoComposerInstaller\Listener\PackagePrioritySortListener;
use Jh\MagentoComposerInstaller\ProjectConfig;
use PHPUnit_Framework_TestCase;

/**
 * Class PackagePrioritySortListenerTest
 * @package Jh\MagentoComposerInstaller\Magento\Listener
 * @author  Aydin Hassan <aydin@hotmail.co.uk>
 */
class PackagePrioritySortListenerTest extends PHPUnit_Framework_TestCase
{

    public function testSortWithNoConfigChanges()
    {

        $package1 = new Package("vendor/package1", "1.1.0", "vendor/package1");
        $package2 = new Package("vendor/package2", "1.1.0", "vendor/package2");
        $packages = new ArrayObject([$package1, $package2]);

        $config = new ProjectConfig([], []);
        $installStrategyFactory = new InstallStrategyFactory($config);

        $listener = new PackagePrioritySortListener($installStrategyFactory, $config);
        $listener->__invoke(new InstallEvent('pre-install', $packages));

        $this->assertEquals(
            [
                $package1,
                $package2
            ],
            $packages->getArrayCopy()
        );
    }

    public function testSortWithDifferentInstallStrategies()
    {

        $package1 = new Package("vendor/package1", "1.1.0", "vendor/package1");
        $package2 = new Package("vendor/package2", "1.1.0", "vendor/package2");
        $packages = new ArrayObject([$package1, $package2]);

        $config = new ProjectConfig(
            [
                'install-strategy-overwrites' => [
                    'vendor/package1' => 'symlink',
                    'vendor/package2' => 'copy',
                ],
            ]
        );
        $installStrategyFactory = new InstallStrategyFactory($config);

        $listener = new PackagePrioritySortListener($installStrategyFactory, $config);
        $listener->__invoke(new InstallEvent('pre-install', $packages));

        $this->assertEquals(
            [
                $package2,
                $package1,
            ],
            array_values($packages->getArrayCopy())
        );
    }

    public function testSortWithUserPriorities()
    {

        $package1 = new Package("vendor/package1", "1.1.0", "vendor/package1");
        $package2 = new Package("vendor/package2", "1.1.0", "vendor/package2");
        $package3 = new Package("vendor/package3", "1.1.0", "vendor/package3");
        $packages = new ArrayObject([$package1, $package2, $package3]);

        $config = new ProjectConfig(
            [
                'install-priorities' => [
                    'vendor/package1' => 200,
                    'vendor/package2' => 400,
                    'vendor/package3' => 1000,
                ],
            ]
        );
        $installStrategyFactory = new InstallStrategyFactory($config);

        $listener = new PackagePrioritySortListener($installStrategyFactory, $config);
        $listener->__invoke(new InstallEvent('pre-install', $packages));

        $this->assertEquals(
            [
                $package3,
                $package2,
                $package1,
            ],
            array_values($packages->getArrayCopy())
        );
    }
}

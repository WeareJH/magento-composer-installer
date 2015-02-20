<?php

namespace Jh\MagentoComposerInstallerTest\Factory;

use Composer\Package\Package;
use Jh\MagentoComposerInstaller\Factory\InstallStrategyFactory;
use Jh\MagentoComposerInstaller\ProjectConfig;

/**
 * Class InstallStrategyFactoryTest
 * @package Jh\MagentoComposerInstaller\Magento\Factory
 * @author  Aydin Hassan <aydin@hotmail.co.uk>
 */
class InstallStrategyFactoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider strategyProvider
     * @param string $strategy
     * @param string $expectedClass
     */
    public function testCorrectDeployStrategyIsReturned($strategy, $expectedClass)
    {
        $package = new Package("some/package", "1.0.0", "some/package");
        $config = new ProjectConfig(['install-strategy' => $strategy,]);
        $factory = new InstallStrategyFactory($config);
        $instance = $factory->make($package);
        $this->assertInstanceOf($expectedClass, $instance);
    }

    /**
     * @return array
     */
    public function strategyProvider()
    {
        return [
            ['copy',    '\Jh\MagentoComposerInstaller\InstallStrategy\Copy'],
            ['symlink', '\Jh\MagentoComposerInstaller\InstallStrategy\Symlink'],
            ['link',    '\Jh\MagentoComposerInstaller\InstallStrategy\Link'],
            ['none',    '\Jh\MagentoComposerInstaller\InstallStrategy\None'],
        ];
    }

    public function testSymlinkStrategyIsUsedIfConfiguredStrategyNotFound()
    {
        $package = new Package("some/package", "1.0.0", "some/package");
        $config = new ProjectConfig(['magento-deploystrategy' => 'lolnotarealstrategy',], []);
        $factory = new InstallStrategyFactory($config);

        $instance = $factory->make($package);
        $this->assertInstanceOf('\Jh\MagentoComposerInstaller\InstallStrategy\Symlink', $instance);
    }

    public function testIndividualOverrideTakesPrecedence()
    {
        $package = new Package("some/package", "1.0.0", "some/package");
        $config = new ProjectConfig([
            'install-strategy' => 'symlink',
            'install-strategy-overwrites' => ['some/package' => 'none'],
        ]);

        $factory = new InstallStrategyFactory($config);

        $instance = $factory->make($package);
        $this->assertInstanceOf('\Jh\MagentoComposerInstaller\InstallStrategy\None', $instance);
    }

    /**
     * @dataProvider strategyProvider
     * @param string $strategy
     */
    public function testDetermineStrategy($strategy)
    {
        $package = new Package("some/package", "1.0.0", "some/package");
        $config = new ProjectConfig(['install-strategy' => $strategy,]);
        $factory = new InstallStrategyFactory($config);
        $name = $factory->determineStrategy($package);
        $this->assertSame($strategy, $name);
    }

    /**
     * @dataProvider priorityProvider
     * @param string $strategy
     */
    public function testDefaultPriorities($strategy, $priority)
    {
        $package = new Package("some/package", "1.0.0", "some/package");
        $config = new ProjectConfig(['install-strategy' => $strategy]);
        $factory = new InstallStrategyFactory($config);
        $this->assertSame($priority, $factory->getDefaultPriority($package));
    }

    /**
     * @return array
     */
    public function priorityProvider()
    {
        return [
            ['copy',    101],
            ['symlink', 100],
            ['link',    100],
            ['none',    100],
        ];
    }
}

/**
 * Override realpath function so we can force it to work with vfsStream
 *
 * @param string $path
 * @return string
 */
function realpath($path)
{
    return $path;
}

<?php

namespace Jh\MagentoComposerInstallerTest\Factory;

use Composer\Package\Package;
use Jh\MagentoComposerInstaller\Factory\ParserFactory;
use Jh\MagentoComposerInstaller\ProjectConfig;
use org\bovigo\vfs\vfsStream;

/**
 * Class ParserFactoryTest
 * @package Jh\MagentoComposerInstaller\Magento\Parser
 * @author  Aydin Hassan <aydin@hotmail.co.uk>
 */
class ParserFactoryTest extends \PHPUnit_Framework_TestCase
{
    protected $root;

    public function setUp()
    {
        $this->root = vfsStream::setup('root');
    }

    public function testMapParserIsReturnedIfMapOverwriteFound()
    {
        $package = new Package('module-package', '1.0.0', 'module-package');
        $config = new ProjectConfig(['map-overwrites' => ['module-package' => []]]);
        $factory = new ParserFactory($config);
        $instance = $factory->make($package, vfsStream::url('root'));
        $this->assertInstanceOf('Jh\MagentoComposerInstaller\Parser\MapParser', $instance);
    }

    public function testMapParserIsReturnIfModuleMapFound()
    {

        $package = new Package('module-package', '1.0.0', 'module-package');
        $package->setExtra(['map' => []]);

        $config = new ProjectConfig([], []);
        $factory = new ParserFactory($config);
        $instance = $factory->make($package, vfsStream::url('root'));
        $this->assertInstanceOf('Jh\MagentoComposerInstaller\Parser\MapParser', $instance);
    }

    public function testPackageXmlParserIsReturnedIfPackageXmlKeyIsFound()
    {

        vfsStream::newFile('Package.xml')->at($this->root);
        $package = new Package('module-package', '1.0.0', 'module-package');
        $package->setExtra(['package-xml' => 'Package.xml']);

        $config = new ProjectConfig([], []);
        $factory = new ParserFactory($config);
        $instance = $factory->make($package, vfsStream::url('root'));
        $this->assertInstanceOf('Jh\MagentoComposerInstaller\Parser\PackageXmlParser', $instance);
    }

    public function testModmanParserIsReturnedIfModmanFileIsFound()
    {

        vfsStream::newFile('modman')->at($this->root);
        $package = new Package('module-package', '1.0.0', 'module-package');

        $config = new ProjectConfig([], []);
        $factory = new ParserFactory($config);
        $instance = $factory->make($package, vfsStream::url('root'));
        $this->assertInstanceOf('Jh\MagentoComposerInstaller\Parser\ModmanParser', $instance);
    }

    public function testExceptionIsThrownIfNoParserConditionsAreMet()
    {
        $this->setExpectedException(
            'ErrorException',
            'Unable to find deploy strategy for module: "module-package" no known mapping'
        );

        $package = new Package('module-package', '1.0.0', 'module-package');

        $config = new ProjectConfig([], []);
        $factory = new ParserFactory($config);
        $instance = $factory->make($package, vfsStream::url('root'));
    }
}

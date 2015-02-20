<?php

namespace Jh\MagentoComposerInstallerTest\Factory;

use Composer\Package\Package;
use Composer\Package\RootPackage;
use Jh\MagentoComposerInstaller\Factory\PathTranslationParserFactory;
use Jh\MagentoComposerInstaller\ProjectConfig;
use org\bovigo\vfs\vfsStream;

/**
 * Class PathTranslationParserFactoryTest
 * @package Jh\MagentoComposerInstaller\Magento\Parser
 * @author  Aydin Hassan <aydin@hotmail.co.uk>
 */
class PathTranslationParserFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testFactoryReturnsInstanceOfPathTranslationParserIfConfigSet()
    {
        vfsStream::setup('root');

        $package = new Package('module-package', '1.0.0', 'module-package');

        $extra = ['path-mapping-translations' => ['module-package' => []]];
        $config = new ProjectConfig($extra);

        $mockParserFactory = $this->getMock('Jh\MagentoComposerInstaller\Factory\ParserFactoryInterface');
        $mockParserFactory
            ->expects($this->once())
            ->method('make')
            ->with($package, vfsStream::url('root'))
            ->will($this->returnValue($this->getMock('Jh\MagentoComposerInstaller\Parser\ParserInterface')));

        $factory = new PathTranslationParserFactory($mockParserFactory, $config);
        $instance = $factory->make($package, vfsStream::url('root'));

        $this->assertInstanceOf('Jh\MagentoComposerInstaller\Parser\PathTranslationParser', $instance);
    }

    public function testFactoryReturnsEmbeddedParserIfNoTranslationsFoundInConfig()
    {
        $package = new Package('module-package', '1.0.0', 'module-package');
        $config = new ProjectConfig([], []);

        $parser = $this->getMock('Jh\MagentoComposerInstaller\Parser\ParserInterface');

        $mockParserFactory = $this->getMock('Jh\MagentoComposerInstaller\Factory\ParserFactoryInterface');
        $mockParserFactory
            ->expects($this->once())
            ->method('make')
            ->with($package, vfsStream::url('root'))
            ->will($this->returnValue($parser));

        $factory = new PathTranslationParserFactory($mockParserFactory, $config);
        $instance = $factory->make($package, vfsStream::url('root'));

        $this->assertSame($parser, $instance);
        $this->assertNotInstanceOf('Jh\MagentoComposerInstaller\Parser\PathTranslationParser', $instance);
    }
}

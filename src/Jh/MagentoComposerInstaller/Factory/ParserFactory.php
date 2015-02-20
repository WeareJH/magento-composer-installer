<?php

namespace Jh\MagentoComposerInstaller\Factory;

use Composer\Package\PackageInterface;
use Jh\MagentoComposerInstaller\Parser\MapParser;
use Jh\MagentoComposerInstaller\Parser\ModmanParser;
use Jh\MagentoComposerInstaller\Parser\PackageXmlParser;
use Jh\MagentoComposerInstaller\Parser\ParserInterface;
use Jh\MagentoComposerInstaller\ProjectConfig;

/**
 * Class ParserFactory
 * @package Jh\MagentoComposerInstaller\Magento\Factory
 * @author  Aydin Hassan <aydin@hotmail.co.uk>
 */
class ParserFactory implements ParserFactoryInterface
{

    /**
     * @var ProjectConfig
     */
    protected $config;

    /**
     */
    public function __construct(ProjectConfig $config)
    {
        $this->config = $config;
    }

    /**
     * @param PackageInterface $package
     * @param string $sourceDir
     * @return ParserInterface
     * @throws \ErrorException
     */
    public function make(PackageInterface $package, $sourceDir)
    {
        $moduleSpecificMap = $this->config->getMapOverwrites();
        if (isset($moduleSpecificMap[$package->getName()])) {
            $map = $moduleSpecificMap[$package->getName()];
            return new MapParser($map);
        }

        $extra = $package->getExtra();
        if (isset($extra['map'])) {
            return new MapParser($extra['map']);
        }

        if (isset($extra['package-xml'])) {
            return new PackageXmlParser(sprintf('%s/%s', $sourceDir, $extra['package-xml']));
        }

        $modmanFile = sprintf('%s/modman', $sourceDir);
        if (file_exists($modmanFile)) {
            return new ModmanParser($modmanFile);
        }

        throw new \ErrorException(
            sprintf('Unable to find deploy strategy for module: "%s" no known mapping', $package->getName())
        );
    }
}

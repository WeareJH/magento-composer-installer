<?php

namespace Jh\MagentoComposerInstaller\Parser;

use Composer\Package\PackageInterface;
use Jh\MagentoComposerInstaller\Factory\ParserFactoryInterface;
use Jh\MagentoComposerInstaller\Map\Map;
use Jh\MagentoComposerInstaller\Map\MapCollection;

/**
 * Class Parser
 * @package Jh\MagentoComposerInstaller\Magento\Parser
 * @author  Aydin Hassan <aydin@hotmail.co.uk>
 */
class Parser
{

    /**
     * @var ParserFactoryInterface
     */
    protected $parserFactory;

    /**
     * @param ParserFactoryInterface $parserFactory
     */
    public function __construct(ParserFactoryInterface $parserFactory)
    {
        $this->parserFactory = $parserFactory;
    }

    /**
     * @param PackageInterface $package
     * @param string           $packageSourceDirectory
     * @param string           $installDirectory
     *
     * @return MapCollection
     */
    public function getMappings(PackageInterface $package, $packageSourceDirectory, $installDirectory)
    {
        $mapParser = $this->parserFactory->make($package, $packageSourceDirectory);

        $maps = array_map(
            function (array $map) use ($packageSourceDirectory, $installDirectory) {
                return new Map($map[0], $map[1], $packageSourceDirectory, $installDirectory);
            },
            $mapParser->getMappings()
        );

        return new MapCollection($maps);
    }
}

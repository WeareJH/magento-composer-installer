<?php

namespace Jh\MagentoComposerInstaller\Installer;

use Composer\Package\PackageInterface;
use Jh\MagentoComposerInstaller\Event\EventManager;
use Jh\MagentoComposerInstaller\Factory\InstallStrategyFactory;
use Jh\MagentoComposerInstaller\InstallStrategy\Exception\TargetExistsException;
use Jh\MagentoComposerInstaller\InstallStrategy\InstallStrategyInterface;
use Jh\MagentoComposerInstaller\Map\Map;
use Jh\MagentoComposerInstaller\Map\MapCollection;
use Jh\MagentoComposerInstaller\Parser\Parser;
use Jh\MagentoComposerInstaller\ProjectConfig;
use Jh\MagentoComposerInstaller\Util\FileSystem;

/**
 * Class Installer
 * @package Jh\MagentoComposerInstaller\Magento
 * @author  Aydin Hassan <aydin@hotmail.co.uk>
 */
class Installer implements InstallerInterface
{

    /**
     * @var InstallStrategyFactory
     */
    protected $installStrategyFactory;

    /**
     * @var FileSystem
     */
    protected $fileSystem;

    /**
     * @var ProjectConfig
     */
    protected $config;

    /**
     * @var GlobResolver
     */
    protected $globResolver;

    /**
     * @var TargetFilter
     */
    protected $targetFilter;

    /**
     * @var Parser
     */
    protected $parser;

    /**
     * @var EventManager
     */
    protected $eventManager;

    /**
     * @param InstallStrategyFactory $installStrategyFactory
     * @param FileSystem             $fileSystem
     * @param ProjectConfig          $config
     * @param GlobResolver           $globResolver
     * @param TargetFilter           $targetFilter
     * @param Parser                 $parser
     * @param EventManager           $eventManager
     */
    public function __construct(
        InstallStrategyFactory $installStrategyFactory,
        FileSystem $fileSystem,
        ProjectConfig $config,
        GlobResolver $globResolver,
        TargetFilter $targetFilter,
        Parser $parser,
        EventManager $eventManager
    ) {
        $this->installStrategyFactory   = $installStrategyFactory;
        $this->fileSystem               = $fileSystem;
        $this->config                   = $config;
        $this->globResolver             = $globResolver;
        $this->targetFilter             = $targetFilter;
        $this->parser                   = $parser;
        $this->eventManager             = $eventManager;
    }

    /**
     * Delegate installation to the particular strategy
     *
     * @param PackageInterface $package
     * @param string $packageSourceDirectory
     * @return MapCollection
     * @throws \ErrorException
     */
    public function install(PackageInterface $package, $packageSourceDirectory)
    {
        $installStrategy = $this->installStrategyFactory->make($package);
        $force           = $this->config->getForceInstallByPackageName($package->getName());

        $mappings = $this->parser->getMappings(
            $package,
            $packageSourceDirectory,
            $this->config->getMagentoRootDir()
        );

        //lets expand glob mappings first
        $mappings = $this->globResolver->resolve($mappings);

        $this->createMissingDirectories($mappings);

        $mappings = $this->resolveMappings($mappings, $installStrategy);
        $mappings = $this->removeIgnoredMappings($package, $mappings);
        $mappings = $this->removeNonExistingSourceMappings($mappings);

        foreach ($mappings as $map) {
            /** @var Map $map */
            $this->fileSystem->ensureDirectoryExists(dirname($map->getAbsoluteDestination()));
            try {
                $installStrategy->create($map, $force);
            } catch (TargetExistsException $e) {
                //dispath event so console can log?
                //re-throw for now
                throw $e;
            }
        }

        return $mappings;
    }

    /**
     * If raw Map destination ends with a directory separator,
     * source is not a directory and the destination file does not exist
     * create destination s a directory
     *
     * @param MapCollection $mappings
     */
    protected function createMissingDirectories(MapCollection $mappings)
    {
        foreach ($mappings as $map) {
            /** @var Map $map */

            // Create target directory if it ends with a directory separator
            if ($this->fileSystem->endsWithDirectorySeparator($map->getRawDestination())
                && !is_dir($map->getAbsoluteSource())
                && !file_exists($map->getAbsoluteDestination())
            ) {
                $this->fileSystem->ensureDirectoryExists($map->getAbsoluteDestination());
            }
        }
    }

    /**
     * @param MapCollection            $mappings
     * @param InstallStrategyInterface $installStrategy
     * @return MapCollection
     */
    protected function resolveMappings(MapCollection $mappings, InstallStrategyInterface $installStrategy)
    {
        $replacementItems = [];
        foreach ($mappings as $map) {
            /** @var Map $map */
            $resolvedMappings = $installStrategy->resolve(
                $map->getSource(),
                $map->getDestination(),
                $map->getAbsoluteSource(),
                $map->getAbsoluteDestination()
            );

            $maps = array_map(
                function (array $mapping) use ($map) {
                    return new Map($mapping[0], $mapping[1], $map->getSourceRoot(), $map->getDestinationRoot());
                },
                $resolvedMappings
            );

            $replacementItems = array_merge($replacementItems, $maps);
        }

        return new MapCollection($replacementItems);
    }

    /**
     * @param MapCollection $mappings
     * @return MapCollection
     */
    protected function removeNonExistingSourceMappings(MapCollection $mappings)
    {
        //remove mappings where source doesn't exist
        return $mappings->filter(function (Map $map) {
            //throw exceptions for missing source?
            //trigger event and log?
            return file_exists($map->getAbsoluteSource());
        });
    }

    /**
     * @param PackageInterface $package
     * @param MapCollection    $mappings
     * @return MapCollection
     */
    protected function removeIgnoredMappings(PackageInterface $package, MapCollection $mappings)
    {
        $targetFilter = $this->targetFilter;
        return $mappings->filter(function (Map $map) use ($package, $targetFilter) {
            return !$targetFilter->isTargetIgnored($package, $map->getDestination());
        });
    }
}

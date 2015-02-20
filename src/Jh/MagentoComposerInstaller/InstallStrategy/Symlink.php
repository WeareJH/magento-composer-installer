<?php

namespace Jh\MagentoComposerInstaller\InstallStrategy;

use Jh\MagentoComposerInstaller\InstallStrategy\Exception\TargetExistsException;
use Jh\MagentoComposerInstaller\Map\Map;
use Jh\MagentoComposerInstaller\Util\FileSystem;

/**
 * Class Symlink
 * @package Jh\MagentoComposerInstaller\InstallStrategy
 */
class Symlink implements InstallStrategyInterface
{
    /**
     * @var FileSystem
     */
    protected $fileSystem;

    /**
     * @param FileSystem $fileSystem
     */
    public function __construct(FileSystem $fileSystem)
    {
        $this->fileSystem = $fileSystem;
    }

    /**
     * Resolve the mappings.
     *
     * For symlinks, if the destination exists and is a directory, the symlink
     * should be created *inside* destination
     *
     * @param string $source
     * @param string $destination
     * @param string $absoluteSource
     * @param string $absoluteDestination
     *
     * @return array Resolved Mappings
     */
    public function resolve($source, $destination, $absoluteSource, $absoluteDestination)
    {
        if (is_dir($absoluteDestination)) {
            $destination = sprintf('%s/%s', $destination, basename($source));
        }

        return [[$source, $destination]];
    }

    /**
     * @param Map   $map
     * @param bool  $force
     * @throws TargetExistsException
     */
    public function create(Map $map, $force)
    {
        //if destination exists should we overwrite it?
        if (is_dir($map->getAbsoluteDestination())
            && $this->fileSystem->sourceAndDestinationBaseMatch($map->getSource(), $map->getDestination())
        ) {
            if (!$force) {
                throw new TargetExistsException($map->getAbsoluteDestination());
            }
            $this->fileSystem->remove($map->getAbsoluteDestination());
        }

        return $this->symlink($map, $force);
    }

    /**
     * @param Map  $map
     * @param bool $force
     * @throws TargetExistsException
     */
    protected function symlink(Map $map, $force)
    {
        if (is_link($map->getAbsoluteDestination())) {
            $symLinkCorrect = $this->fileSystem->symLinkPointsToCorrectLocation(
                $map->getAbsoluteDestination(),
                $map->getAbsoluteSource()
            );

            if ($symLinkCorrect) {
                return;
            }
            $this->fileSystem->remove($map->getAbsoluteDestination());
        }

        // If file exists and force is not specified, throw exception unless FORCE is set
        // existing symlinks are already handled
        if (file_exists($map->getAbsoluteDestination())) {
            if (!$force) {
                throw new TargetExistsException($map->getAbsoluteDestination());
            }
            $this->fileSystem->remove($map->getAbsoluteDestination());
        }

        $this->fileSystem->createSymlink($map->getAbsoluteSource(), $map->getAbsoluteDestination());
    }
}

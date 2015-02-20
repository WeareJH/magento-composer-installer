<?php

namespace Jh\MagentoComposerInstaller\UnInstallStrategy;

use Jh\MagentoComposerInstaller\Map\Map;
use Jh\MagentoComposerInstaller\Map\MapCollection;
use Jh\MagentoComposerInstaller\Util\Filesystem;

/**
 * Class UnInstallStrategy
 * @package Jh\MagentoComposerInstaller\Magento\UnInstallStrategy
 * @author  Aydin Hassan <aydin@hotmail.co.uk>
 */
class UnInstallStrategy implements UnInstallStrategyInterface
{

    /**
     * @var FileSystem
     */
    protected $fileSystem;

    /**
     * The root dir for uninstalling from. Should be project root.
     *
     * @var string
     */
    protected $rootDir;

    /**
     * @param FileSystem $fileSystem
     * @param string $rootDir
     */
    public function __construct(FileSystem $fileSystem, $rootDir)
    {
        $this->fileSystem   = $fileSystem;
        $this->rootDir      = $rootDir;
    }

    /**
     * UnInstall the extension given the list of install files
     *
     * @param MapCollection $collection
     */
    public function unInstall(MapCollection $collection)
    {
        foreach ($collection as $map) {
            /** @var Map $map */
            $this->fileSystem->unlink($map->getAbsoluteDestination());

            if ($this->fileSystem->isDirEmpty(dirname($map->getAbsoluteDestination()))) {
                $this->fileSystem->removeEmptyDirectoriesUpToRoot(
                    dirname($map->getAbsoluteDestination()),
                    $this->rootDir
                );
            }
        }
    }
}

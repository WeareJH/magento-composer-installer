<?php

namespace Jh\MagentoComposerInstaller\InstallStrategy;

use Jh\MagentoComposerInstaller\Map\Map;

/**
 * Class None
 * @package Jh\MagentoComposerInstaller\InstallStrategy
 */
class None implements InstallStrategyInterface
{


    /**
     * @param string $source
     * @param string $destination
     * @param string $absoluteSource
     * @param string $absoluteDestination
     *
     * @return array Resolved Mappings
     */
    public function resolve($source, $destination, $absoluteSource, $absoluteDestination)
    {
        return [];
    }

    /**
     * Deploy Nothing
     *
     * @param Map   $map
     * @param bool  $force
     * @throws TargetExistsException
     */
    public function create(Map $map, $force)
    {
        return;
    }
}

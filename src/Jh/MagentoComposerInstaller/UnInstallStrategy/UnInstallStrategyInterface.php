<?php

namespace Jh\MagentoComposerInstaller\UnInstallStrategy;

use Jh\MagentoComposerInstaller\Map\MapCollection;

/**
 * Interface UnInstallStrategyInterface
 * @package Jh\MagentoComposerInstaller\Magento\UnInstallStrategy
 * @author  Aydin Hassan <aydin@hotmail.co.uk>
 */
interface UnInstallStrategyInterface
{
    /**
     * UnInstall the extension given the list of install files
     * @param MapCollection $collection
     */
    public function unInstall(MapCollection $collection);
}

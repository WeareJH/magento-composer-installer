<?php

namespace Jh\MagentoComposerInstaller\Installer;

use Composer\Package\PackageInterface;
use Jh\MagentoComposerInstaller\Map\MapCollection;

/**
 * Interface InstallerInterface
 * @package Jh\MagentoComposerInstaller\Magento\Installer
 * @author Aydin Hassan <aydin@hotmail.co.uk>
 */
interface InstallerInterface
{
    /**
     * @param PackageInterface $package
     * @param string           $packageSourceDirectory
     *
     * @return MapCollection
     */
    public function install(PackageInterface $package, $packageSourceDirectory);
}

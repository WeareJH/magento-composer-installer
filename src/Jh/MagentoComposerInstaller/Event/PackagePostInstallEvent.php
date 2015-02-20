<?php

namespace Jh\MagentoComposerInstaller\Event;

use Composer\EventDispatcher\Event;
use Composer\Package\PackageInterface;
use Jh\MagentoComposerInstaller\InstalledPackage;
use Jh\MagentoComposerInstaller\Map\MapCollection;

/**
 * Class PackagePostInstallEvent
 * @package Jh\MagentoComposerInstaller\Magento\Event
 * @author  Aydin Hassan <aydin@hotmail.co.uk>
 */
class PackagePostInstallEvent extends Event
{
    /**
     * @var PackageInterface
     */
    protected $composerPackage;

    /**
     * @var InstalledPackage
     */
    protected $installedPackage;

    /**
     * @param PackageInterface $composerPackage
     * @param InstalledPackage $installedPackage
     */
    public function __construct(PackageInterface $composerPackage, InstalledPackage $installedPackage)
    {
        parent::__construct('package-post-install');
        $this->composerPackage  = $composerPackage;
        $this->installedPackage = $installedPackage;
    }

    /**
     * @return PackageInterface
     */
    public function getPackage()
    {
        return $this->composerPackage;
    }

    /**
     * @return MapCollection
     */
    public function getMappings()
    {
        return $this->installedPackage->getMappings();
    }
}

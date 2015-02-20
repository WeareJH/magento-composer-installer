<?php

namespace Jh\MagentoComposerInstaller\Event;

use Composer\EventDispatcher\Event;
use Jh\MagentoComposerInstaller\InstalledPackage;
use Jh\MagentoComposerInstaller\Map\MapCollection;

/**
 * Class PackageUnInstallEvent
 * @package Jh\MagentoComposerInstaller\Magento\Event
 * @author  Aydin Hassan <aydin@hotmail.co.uk>
 */
class PackageUnInstallEvent extends Event
{
    /**
     * @var InstalledPackage
     */
    protected $package;

    /**
     * @param string           $name
     * @param InstalledPackage $package
     */
    public function __construct($name, InstalledPackage $package)
    {
        parent::__construct($name);
        $this->package = $package;
    }

    /**
     * @return InstalledPackage
     */
    public function getPackage()
    {
        return $this->package;
    }

    /**
     * @return MapCollection
     */
    public function getMappings()
    {
        return $this->package->getMappings();
    }
}

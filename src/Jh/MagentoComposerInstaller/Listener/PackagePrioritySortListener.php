<?php

namespace Jh\MagentoComposerInstaller\Listener;

use Composer\Package\PackageInterface;
use Jh\MagentoComposerInstaller\Event\InstallEvent;
use Jh\MagentoComposerInstaller\Factory\InstallStrategyFactory;
use Jh\MagentoComposerInstaller\ProjectConfig;

/**
 * Class PackagePrioritySortListener
 * @package Jh\MagentoComposerInstaller\Magento\Listener
 * @author  Aydin Hassan <aydin@hotmail.co.uk>
 */
class PackagePrioritySortListener
{
    /**
     * @var InstallStrategyFactory
     */
    protected $installStrategyFactory;

    /**
     * @var ProjectConfig
     */
    protected $config;

    /**
     * @param InstallStrategyFactory $installStrategyFactory
     * @param ProjectConfig          $config
     */
    public function __construct(
        InstallStrategyFactory $installStrategyFactory,
        ProjectConfig $config
    ) {

        $this->installStrategyFactory = $installStrategyFactory;
        $this->config = $config;
    }

    /**
     * @param InstallEvent $event
     */
    public function __invoke(InstallEvent $event)
    {
        $packagesToInstall  = $event->getPackages();
        $userPriorities     = $this->config->getInstallPriorities();
        $priorities         = [];
        foreach ($packagesToInstall as $package) {
            /** @var PackageInterface $package */
            if (isset($userPriorities[$package->getName()])) {
                $priority = $userPriorities[$package->getName()];
            } else {
                $priority = $this->installStrategyFactory->getDefaultPriority($package);
            }

            $priorities[$package->getName()] = $priority;
        }

        $packagesToInstall->uasort(
            function (PackageInterface $a, PackageInterface $b) use ($priorities) {
                $aVal = $priorities[$a->getName()];
                $bVal = $priorities[$b->getName()];

                if ($aVal === $bVal) {
                    return 0;
                }
                return ($aVal > $bVal) ? -1 : 1;
            }
        );
    }
}

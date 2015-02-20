<?php

namespace Jh\MagentoComposerInstaller;

use Composer\Config;
use Composer\Installer;
use Composer\Script\CommandEvent;
use Jh\MagentoComposerInstaller\Event\EventManager;
use Jh\MagentoComposerInstaller\Factory\ModuleManagerFactory;
use Composer\Composer;
use Composer\IO\IOInterface;
use Composer\Package\PackageInterface;
use Composer\Plugin\PluginInterface;
use Composer\EventDispatcher\EventSubscriberInterface;
use Composer\Script\ScriptEvents;

/**
 * Class Plugin
 * @package Jh\MagentoComposerInstaller\Magento
 */
class Plugin implements PluginInterface, EventSubscriberInterface
{
    /**
     * The type of packages this plugin supports
     */
    const PACKAGE_TYPE = 'magento-module';

    /**
     * @var IOInterface
     */
    protected $io;

    /**
     * @var ProjectConfig
     */
    protected $config;

    /**
     * @var Composer
     */
    protected $composer;

    /**
     * @var EventManager
     */
    protected $eventManager;

    /**
     * @var ModuleManager
     */
    protected $moduleManager;

    /**
     * Apply plugin modifications to composer
     *
     * @param Composer    $composer
     * @param IOInterface $io
     */
    public function activate(Composer $composer, IOInterface $io)
    {
        $this->io               = $io;
        $this->composer         = $composer;
        $extra                  = $composer->getPackage()->getExtra();
        $config                 = isset($extra['mci']) ? $extra['mci'] : [];
        $config['vendor-dir']   = $composer->getConfig()->get('vendor-dir');
        $this->config           = new ProjectConfig($config);
        $this->eventManager     = new EventManager;
        $moduleManagerFactory   = new ModuleManagerFactory;
        $this->moduleManager    = $moduleManagerFactory->make($this->config, $this->eventManager, $io);

        if ($io->isDebug()) {
            $io->write('Activate Magento plugin');
        }
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            ScriptEvents::POST_INSTALL_CMD => [
                ['onNewCodeEvent', 0],
            ],
            ScriptEvents::POST_UPDATE_CMD  => [
                ['onNewCodeEvent', 0],
            ],
        ];
    }

    /**
     * This event is triggered after installing or updating composer
     *
     * @param CommandEvent $event
     */
    public function onNewCodeEvent(CommandEvent $event)
    {
        $magentoModules = array_filter(
            $this->composer->getRepositoryManager()->getLocalRepository()->getPackages(),
            function (PackageInterface $package) {
                return $package->getType() === static::PACKAGE_TYPE;
            }
        );

        $this->moduleManager->updateInstalledPackages($magentoModules);
    }
}

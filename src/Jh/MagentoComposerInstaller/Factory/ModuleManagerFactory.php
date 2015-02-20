<?php

namespace Jh\MagentoComposerInstaller\Factory;

use Composer\IO\IOInterface;
use Jh\MagentoComposerInstaller\Event\EventManager;
use Jh\MagentoComposerInstaller\Event\PackagePreInstallEvent;
use Jh\MagentoComposerInstaller\GitIgnore;
use Jh\MagentoComposerInstaller\InstalledPackageDumper;
use Jh\MagentoComposerInstaller\Listener\CheckAndCreateMagentoRootDirListener;
use Jh\MagentoComposerInstaller\Listener\GitIgnoreListener;
use Jh\MagentoComposerInstaller\Listener\PackagePrioritySortListener;
use Jh\MagentoComposerInstaller\ModuleManager;
use Jh\MagentoComposerInstaller\ProjectConfig;
use Jh\MagentoComposerInstaller\Repository\InstalledPackageFileSystemRepository;
use Jh\MagentoComposerInstaller\UnInstallStrategy\UnInstallStrategy;
use Jh\MagentoComposerInstaller\Util\FileSystem;

/**
 * Class ModuleManagerFactory
 * @package Jh\MagentoComposerInstaller\Magento\Factory
 * @author  Aydin Hassan <aydin@hotmail.co.uk>
 */
class ModuleManagerFactory
{
    /**
     * @param ProjectConfig $config
     * @param EventManager  $eventManager
     * @param IOInterface   $io
     *
     * @return ModuleManager
     */
    public function make(ProjectConfig $config, EventManager $eventManager, IOInterface $io)
    {
        $installStrategyFactory = new InstallStrategyFactory($config);

        if ($config->manageGitIgnore()) {
            $this->addGitIgnoreListener($eventManager, $config);
        }

        if ($io->isDebug()) {
            $this->addDebugListener($eventManager, $io);
        }

        $eventManager->listen(
            'pre-install',
            new CheckAndCreateMagentoRootDirListener($config->getMagentoRootDir(false))
        );

        $eventManager->listen(
            'pre-install',
            new PackagePrioritySortListener($installStrategyFactory, $config)
        );

        $installerFactory = new InstallerFactory;
        return new ModuleManager(
            new InstalledPackageFileSystemRepository(
                $config->getModuleRepositoryLocation(),
                new InstalledPackageDumper
            ),
            $eventManager,
            $config,
            new UnInstallStrategy(new FileSystem, $config->getMagentoRootDir()),
            $installerFactory->make($config, $eventManager),
            $installStrategyFactory
        );
    }

    /**
     * @param EventManager $eventManager
     * @param ProjectConfig $config
     */
    protected function addGitIgnoreListener(EventManager $eventManager, ProjectConfig $config)
    {
        $gitIgnoreLocation  = sprintf('%s/.gitignore', $config->getMagentoRootDir());
        $gitIgnore          = new GitIgnoreListener(new GitIgnore($gitIgnoreLocation));

        $eventManager->listen('package-post-install', [$gitIgnore, 'addNewInstalledFiles']);
        $eventManager->listen('package-post-uninstall', [$gitIgnore, 'removeUnInstalledFiles']);
    }

    /**
     * @param EventManager $eventManager
     * @param IOInterface  $io
     */
    protected function addDebugListener(EventManager $eventManager, IOInterface $io)
    {
        $eventManager->listen('package-pre-install', function (PackagePreInstallEvent $event) use ($io) {
            $io->write('Start magento deploy for ' . $event->getPackage()->getName());
        });
    }
}

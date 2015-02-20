<?php

namespace Jh\MagentoComposerInstaller\Factory;

use Jh\MagentoComposerInstaller\Event\EventManager;
use Jh\MagentoComposerInstaller\Installer\GlobResolver;
use Jh\MagentoComposerInstaller\Installer\Installer;
use Jh\MagentoComposerInstaller\Installer\TargetFilter;
use Jh\MagentoComposerInstaller\Parser\Parser;
use Jh\MagentoComposerInstaller\ProjectConfig;
use Jh\MagentoComposerInstaller\Util\FileSystem;

/**
 * Class InstallerFactory
 * @package Jh\MagentoComposerInstaller\Magento\Factory
 * @author  Aydin Hassan <aydin@hotmail.co.uk>
 */
class InstallerFactory
{
    /**
     * @param ProjectConfig $config
     * @param EventManager  $eventManager
     *
     * @return Installer
     */
    public function make(ProjectConfig $config, EventManager $eventManager)
    {
        $installStrategyFactory = new InstallStrategyFactory($config);
        $fileSystem             = new FileSystem;
        $globResolver           = new GlobResolver;
        $targetFilter           = new TargetFilter($config->getInstallIgnores());
        $parser                 = new Parser(new ParserFactory($config));

        return new Installer(
            $installStrategyFactory,
            $fileSystem,
            $config,
            $globResolver,
            $targetFilter,
            $parser,
            $eventManager
        );
    }
}

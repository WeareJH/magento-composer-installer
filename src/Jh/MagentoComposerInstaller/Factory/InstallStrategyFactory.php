<?php

namespace Jh\MagentoComposerInstaller\Factory;

use Composer\Package\PackageInterface;
use Jh\MagentoComposerInstaller\InstallStrategy\InstallStrategyInterface;
use Jh\MagentoComposerInstaller\ProjectConfig;
use Jh\MagentoComposerInstaller\Util\FileSystem;

/**
 * Class InstallStrategyFactory
 * @package Jh\MagentoComposerInstaller\Magento\Deploystrategy
 */
class InstallStrategyFactory
{

    /**
     * @var ProjectConfig
     */
    protected $config;

    /**
     * @var ParserFactoryInterface
     */
    protected $parserFactory;

    /**
     * @var array
     */
    protected static $priorityMap = [
        'symlink'   => 100,
        'link'      => 100,
        'copy'      => 101,
    ];

    /**
     * @var array
     */
    protected static $strategies = [
        'copy'      => '\Jh\MagentoComposerInstaller\InstallStrategy\Copy',
        'symlink'   => '\Jh\MagentoComposerInstaller\InstallStrategy\Symlink',
        'link'      => '\Jh\MagentoComposerInstaller\InstallStrategy\Link',
        'none'      => '\Jh\MagentoComposerInstaller\InstallStrategy\None',
    ];

    /**
     * @param ProjectConfig $config
     */
    public function __construct(ProjectConfig $config)
    {
        $this->config = $config;
    }

    /**
     * @param PackageInterface $package
     * @return InstallStrategyInterface
     */
    public function make(PackageInterface $package)
    {
        $strategyName = $this->determineStrategy($package);
        if (!isset(static::$strategies[$strategyName])) {
            $className = static::$strategies['symlink'];
        } else {
            $className = static::$strategies[$strategyName];
        }

        if ($className === static::$strategies['none']) {
            $instance = new $className;
        } else {
            $instance = new $className(new FileSystem);
        }

        return $instance;
    }

    /**
     * @param PackageInterface $package
     * @return string
     */
    public function determineStrategy(PackageInterface $package)
    {
        $strategyName = $this->config->getInstallStrategy();
        if ($this->config->hasInstallStrategyOverwrites()) {
            $moduleSpecificDeployStrategies = $this->config->getInstallStrategyOverwrites();

            if (isset($moduleSpecificDeployStrategies[$package->getName()])) {
                $strategyName = $moduleSpecificDeployStrategies[$package->getName()];
            }
        }

        return $strategyName;
    }

    /**
     * @param PackageInterface $package
     * @return int
     */
    public function getDefaultPriority(PackageInterface $package)
    {
        if (isset(static::$priorityMap[$this->determineStrategy($package)])) {
            return static::$priorityMap[$this->determineStrategy($package)];
        }

        return 100;
    }
}

<?php
/**
 *
 *
 *
 *
 */

namespace Jh\MagentoComposerInstaller;

use Composer\Factory;
use Composer\Json\JsonFile;
use Composer\Json\JsonManipulator;
use SebastianBergmann\Exporter\Exception;

/**
 * Class ProjectConfig
 * @package Jh\MagentoComposerInstaller\Magento
 */
class ProjectConfig
{

    /**
     * @var array
     */
    protected $installPriorities = array();

    /**
     * @var string
     */
    protected $magentoRootDir = 'htdocs';

    /**
     * @var string
     */
    protected $installStrategy = 'symlink';

    /**
     * @var array
     */
    protected $installStrategyOverwrites = array();

    /**
     * @var array
     */
    protected $mapOverwrites = array();

    /**
     * @var array
     */
    protected $installIgnores = array();

    /**
     * @var bool
     */
    protected $forceInstall = false;

    /**
     * @var bool
     */
    protected $manageGitIgnore = true;

    /**
     * @var array
     */
    protected $pathMappingTranslations = array();

    /**
     * @var null|string
     */
    protected $moduleRepositoryLocation = null;

    /**
     * @var string
     */
    protected $vendorDir = 'vendor';

    /**
     * @param array $config
     */
    public function __construct(array $config)
    {
        if (isset($config['install-priorities']) && is_array($config['install-priorities'])) {
            $this->installPriorities = array_change_key_case($config['install-priorities']);
        }

        if (isset($config['magento-root-dir']) && is_string($config['magento-root-dir'])) {
            $this->magentoRootDir = $config['magento-root-dir'];
        }

        if (isset($config['install-strategy']) && is_string($config['install-strategy'])) {
            $this->installStrategy = trim($config['install-strategy']);
        }

        if (isset($config['install-strategy-overwrites']) && is_array($config['install-strategy-overwrites'])) {
            $this->installStrategyOverwrites = array_change_key_case($config['install-strategy-overwrites']);
        }

        if (isset($config['map-overwrites']) && is_array($config['map-overwrites'])) {
            $this->mapOverwrites = array_change_key_case($config['map-overwrites']);
        }

        if (isset($config['install-ignores']) && is_array($config['install-ignores'])) {
            $this->installIgnores = array_change_key_case($config['install-ignores']);
        }

        if (isset($config['force-install']) && is_bool($config['force-install'])) {
            $this->forceInstall = $config['force-install'];
        }

        if (isset($config['disable-gitignore-manage']) && $config['disable-gitignore-manage']) {
            $this->manageGitIgnore = false;
        }

        if (isset($config['path-mapping-translations']) && is_array($config['path-mapping-translations'])) {
            $this->pathMappingTranslations = array_change_key_case($config['path-mapping-translations']);
        }

        if (isset($config['module-repository-location']) && is_string($config['module-repository-location'])) {
            $this->moduleRepositoryLocation = trim($config['module-repository-location']);
        }

        if (isset($config['vendor-dir']) && is_string($config['vendor-dir'])) {
            $this->vendorDir = $config['vendor-dir'];
        }
    }

    /**
     * @param bool $realPath
     * @return string
     */
    public function getMagentoRootDir($realPath = true)
    {
        $rootDir = rtrim(trim($this->magentoRootDir), DIRECTORY_SEPARATOR);

        if ($realPath) {
            return realpath($rootDir);
        }

        return $rootDir;
    }

    /**
     * @return string
     */
    public function getInstallStrategy()
    {
        return $this->installStrategy;
    }

    /**
     * @return array
     */
    public function getInstallStrategyOverwrites()
    {
        return $this->installStrategyOverwrites;
    }

    /**
     * @return bool
     */
    public function hasInstallStrategyOverwrites()
    {
        return count($this->installStrategyOverwrites) > 0;
    }

    /**
     * @return array
     */
    public function getInstallIgnores()
    {
        return $this->installIgnores;
    }

    /**
     * @return bool
     */
    public function hasInstallIgnores()
    {
        return count($this->installIgnores) > 0;
    }

    /**
     * @return bool
     */
    public function getForceInstall()
    {
        return $this->forceInstall;
    }

    /**
     * @param string $packagename
     * @return string
     */
    public function getForceInstallByPackageName($packagename)
    {
        return $this->getForceInstall();
    }

    /**
     * @return bool
     */
    public function manageGitIgnore()
    {
        return $this->manageGitIgnore;
    }

    /**
     * @return array
     */
    public function getPathMappingTranslations()
    {
        return $this->pathMappingTranslations;
    }

    /**
     * @return bool
     */
    public function hasPathMappingTranslations()
    {
        return count($this->pathMappingTranslations) > 0;
    }

    /**
     * @return array
     */
    public function getMapOverwrites()
    {
        return $this->mapOverwrites;
    }

    /**
     * Get MagentoComposerInstaller vendor directory
     *
     * @return string
     */
    public function getVendorDir()
    {
        return $this->vendorDir;
    }

    /**
     * Get Package Sort Order
     *
     * @return array
     */
    public function getInstallPriorities()
    {
        return $this->installPriorities;
    }

    /**
     * @return string
     */
    public function getModuleRepositoryLocation()
    {
        if (null === $this->moduleRepositoryLocation) {
            return sprintf('%s/magento-installed.json', $this->getVendorDir());
        }

        return sprintf('%s/magento-installed.json', $this->moduleRepositoryLocation);
    }
}

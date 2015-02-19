<?php

namespace MagentoHackathon\Composer\Magento;

use PHPUnit_Framework_TestCase;

/**
 * Class ProjectConfigTest
 * @package MagentoHackathon\Composer\Magento
 */
class ProjectConfigTest extends PHPUnit_Framework_TestCase
{
    public function testHasInstallStrategyOverwrite()
    {
        $config = new ProjectConfig(['install-strategy-overwrites' => ['some/module']]);
        $this->assertTrue($config->hasInstallStrategyOverwrites());
        $config = new ProjectConfig([]);
        $this->assertFalse($config->hasInstallStrategyOverwrites());
    }

    public function testHasInstallIgnores()
    {
        $config = new ProjectConfig(['install-ignores' => ['some/module']]);
        $this->assertTrue($config->hasInstallIgnores());
        $config = new ProjectConfig([]);
        $this->assertFalse($config->hasInstallIgnores());
    }

    public function testGitIgnoreManage()
    {
        $config = new ProjectConfig(['disable-gitignore-manage' => true]);
        $this->assertFalse($config->manageGitIgnore());
        $config = new ProjectConfig([]);
        $this->assertTrue($config->manageGitIgnore());
        $config = new ProjectConfig(['disable-gitignore-manage' => false]);
        $this->assertTrue($config->manageGitIgnore());
    }

    public function testHasPathMappingTranslations()
    {
        $config = new ProjectConfig(['path-mapping-translations' => ['some/module']]);
        $this->assertTrue($config->hasPathMappingTranslations());
        $config = new ProjectConfig([]);
        $this->assertFalse($config->hasPathMappingTranslations());
    }

    public function testGetInstallPriorities()
    {
        $config = new ProjectConfig(['install-priorities' => [1]]);
        $this->assertSame([1], $config->getInstallPriorities());
        $config = new ProjectConfig([]);
        $this->assertSame([], $config->getInstallPriorities());
    }

    public function testGetVendorDir()
    {
        $config = new ProjectConfig(['vendor-dir' => 'vendor']);
        $this->assertSame('vendor', $config->getVendorDir());
        $config = new ProjectConfig([]);
        $this->assertSame('vendor', $config->getVendorDir());
    }

    public function testGetMapOverwrites()
    {
        $config = new ProjectConfig(['map-overwrites' => [1]]);
        $this->assertSame([1], $config->getMapOverwrites());
        $config = new ProjectConfig([]);
        $this->assertSame([], $config->getMapOverwrites());
    }

    public function testGetInstallStrategyOverwrites()
    {
        $config = new ProjectConfig(['install-strategy-overwrites' => [1]]);
        $this->assertSame([1], $config->getInstallStrategyOverwrites());
        $config = new ProjectConfig([]);
        $this->assertSame([], $config->getInstallStrategyOverwrites());
    }

    public function testGetPathMappingTranslations()
    {
        $config = new ProjectConfig(['path-mapping-translations' => [1]]);
        $this->assertSame([1], $config->getPathMappingTranslations());
        $config = new ProjectConfig([]);
        $this->assertSame([], $config->getPathMappingTranslations());
    }

    public function testGetForceInstall()
    {
        $config = new ProjectConfig(['force-install' => true]);
        $this->assertTrue($config->getForceInstall());
        $this->assertTrue($config->getForceInstallByPackageName('some/package'));

        $config = new ProjectConfig(['force-install' => false]);
        $this->assertFalse($config->getForceInstall());
        $this->assertFalse($config->getForceInstallByPackageName('some/package'));

        $config = new ProjectConfig(['force-install' => ['lol']]);
        $this->assertFalse($config->getForceInstall());
        $this->assertFalse($config->getForceInstallByPackageName('some/package'));
    }

    public function testGetInstallIgnores()
    {
        $config = new ProjectConfig(['install-ignores' => [1]]);
        $this->assertSame([1], $config->getInstallIgnores());
        $config = new ProjectConfig([]);
        $this->assertSame([], $config->getInstallIgnores());
    }

    public function testGetDeployStrategy()
    {
        $config = new ProjectConfig(['install-strategy' => 'symlink']);
        $this->assertSame('symlink', $config->getInstallStrategy());

        $config = new ProjectConfig(['install-strategy' => ' symlink   ']);
        $this->assertSame('symlink', $config->getInstallStrategy());
    }

    public function testGetMagentoRootDir()
    {
        $config = new ProjectConfig(['magento-root-dir' => '/htdocs']);
        $this->assertSame('/htdocs', $config->getMagentoRootDir(false));

        $config = new ProjectConfig(['magento-root-dir' => 'htdocs']);
        $this->assertSame('htdocs', $config->getMagentoRootDir(false));

        $config = new ProjectConfig([]);
        $this->assertSame('htdocs', $config->getMagentoRootDir(false));
    }

    public function testGetInstalledModuleRepositoryFile()
    {
        $config = new ProjectConfig(['magento-root-dir' => '/htdocs/', 'vendor-dir' => 'vendor']);
        $this->assertSame('vendor/magento-installed.json', $config->getModuleRepositoryLocation());

        $config = new ProjectConfig(['module-repository-location' => 'htdocs', 'vendor-dir' => 'vendor']);
        $this->assertSame('htdocs/magento-installed.json', $config->getModuleRepositoryLocation());
    }
}

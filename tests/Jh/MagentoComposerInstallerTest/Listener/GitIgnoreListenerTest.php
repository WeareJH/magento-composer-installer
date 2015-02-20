<?php

namespace Jh\MagentoComposerInstallerTest\Listener;

use Composer\Package\Package;
use Jh\MagentoComposerInstaller\Event\PackageDeployEvent;
use Jh\MagentoComposerInstaller\Event\PackagePostInstallEvent;
use Jh\MagentoComposerInstaller\Event\PackageUnInstallEvent;
use Jh\MagentoComposerInstaller\GitIgnore;
use Jh\MagentoComposerInstaller\InstalledPackage;
use Jh\MagentoComposerInstaller\Listener\GitIgnoreListener;
use Jh\MagentoComposerInstaller\Map\Map;
use Jh\MagentoComposerInstaller\Map\MapCollection;

/**
 * Class GitIgnoreListenerTest
 * @package Jh\MagentoComposerInstaller\Magento
 * @author  Aydin Hassan <aydin@hotmail.co.uk>
 */
class GitIgnoreListenerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var GitIgnoreListener
     */
    protected $listener;

    /**
     * @var GitIgnore
     */
    protected $gitIgnore;

    public function setUp()
    {
        $this->gitIgnore = $this->getMockBuilder('Jh\MagentoComposerInstaller\GitIgnore')
            ->disableOriginalConstructor()
            ->getMock();

        $this->listener = new GitIgnoreListener($this->gitIgnore);
    }

    public function testAddNewInstalledFiles()
    {
        $map1       = new Map('file1', 'file1', '/tmp/', '/tmp/');
        $map2       = new Map('file2', 'file2', '/tmp/', '/tmp/');
        $map3       = new Map('folder/file3', 'folder/file3', '/tmp/', '/tmp/');
        $collection = new MapCollection([$map1, $map2, $map3]);
        $package    = new Package('some/package', '1.0.0', 'some/package');
        $installedP = new InstalledPackage('some/package', '1.0.0', $collection);
        $event      = new PackagePostInstallEvent($package, $installedP);

        $this->gitIgnore
            ->expects($this->once())
            ->method('addMultipleEntries')
            ->with(['/file1', '/file2', '/folder/file3']);

        $this->gitIgnore
            ->expects($this->once())
            ->method('write');

        $this->listener->addNewInstalledFiles($event);
    }

    public function testRemoveUnInstalledFile()
    {
        $map1       = new Map('file1', 'file1', '/tmp/', '/tmp/');
        $map2       = new Map('file2', 'file2', '/tmp/', '/tmp/');
        $map3       = new Map('folder/file3', 'folder/file3', '/tmp/', '/tmp/');
        $collection = new MapCollection([$map1, $map2, $map3]);
        $package    = new InstalledPackage('some/package', '1.0.0', $collection);
        $event      = new PackageUnInstallEvent('package-uninstall', $package);

        $this->gitIgnore
            ->expects($this->once())
            ->method('removeMultipleEntries')
            ->with(['/file1', '/file2', '/folder/file3']);

        $this->gitIgnore
            ->expects($this->once())
            ->method('write');

        $this->listener->removeUnInstalledFiles($event);
    }
}

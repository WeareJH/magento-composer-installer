<?php

namespace Jh\MagentoComposerInstaller\Listener;

use Jh\MagentoComposerInstaller\Event\InstallEvent;

/**
 * Class CheckAndCreateMagentoRootDirListener
 * @package Jh\MagentoComposerInstaller\Magento\Listener
 * @author  Aydin Hassan <aydin@hotmail.co.uk>
 */
class CheckAndCreateMagentoRootDirListener
{

    /**
     * @var string
     */
    protected $magentoRootDir;

    /**
     * @param string $magentoRootDir
     */
    public function __construct($magentoRootDir)
    {
        $this->magentoRootDir = $magentoRootDir;
    }

    /**
     * @param InstallEvent $event
     */
    public function __invoke(InstallEvent $event)
    {
        if (!file_exists($this->magentoRootDir)) {
            mkdir($this->magentoRootDir);
        }
    }
}

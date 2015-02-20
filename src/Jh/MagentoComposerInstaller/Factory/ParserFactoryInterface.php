<?php

namespace Jh\MagentoComposerInstaller\Factory;

use Composer\Package\PackageInterface;
use Jh\MagentoComposerInstaller\Parser\ParserInterface;

/**
 * Interface ParserFactoryInterface
 * @package Jh\MagentoComposerInstaller\Magento\Parser
 * @author  Aydin Hassan <aydin@hotmail.co.uk>
 */
interface ParserFactoryInterface
{

    /**
     * @param PackageInterface $package
     * @param string $sourceDir
     * @return ParserInterface
     */
    public function make(PackageInterface $package, $sourceDir);
}

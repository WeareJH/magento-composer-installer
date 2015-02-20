<?php

namespace Jh\MagentoComposerInstaller\Factory;

use Composer\Package\PackageInterface;
use Jh\MagentoComposerInstaller\Parser\ParserInterface;
use Jh\MagentoComposerInstaller\Parser\PathTranslationParser;
use Jh\MagentoComposerInstaller\ProjectConfig;

/**
 * Class PathTranslationParserFactory
 * @package Jh\MagentoComposerInstaller\Magento\Parser
 * @author  Aydin Hassan <aydin@hotmail.co.uk>
 */
class PathTranslationParserFactory implements ParserFactoryInterface
{
    /**
     * @var ParserFactoryInterface
     */
    protected $parserFactory;

    /**
     * @var ProjectConfig
     */
    protected $config;

    /**
     * @param ParserFactoryInterface $parserFactory
     */
    public function __construct(ParserFactoryInterface $parserFactory, ProjectConfig $config)
    {
        $this->parserFactory = $parserFactory;
        $this->config = $config;
    }

    /**
     * @param PackageInterface $package
     * @param string $sourceDir
     * @return ParserInterface
     * @throws \ErrorException
     */
    public function make(PackageInterface $package, $sourceDir)
    {
        $parser = $this->parserFactory->make($package, $sourceDir);

        if ($this->config->hasPathMappingTranslations()) {
            $translations = $this->config->getPathMappingTranslations();
            return new PathTranslationParser($parser, $translations);
        }

        return $parser;
    }
}

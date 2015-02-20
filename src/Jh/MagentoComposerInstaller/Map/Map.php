<?php

namespace Jh\MagentoComposerInstaller\Map;

/**
 * Class Map
 * @package Jh\MagentoComposerInstaller\Magento
 * @author  Aydin Hassan <aydin@hotmail.co.uk>
 */
final class Map
{
    /**
     * @var string
     */
    protected $source;

    /**
     * @var string
     */
    protected $destination;

    /**
     * @var string
     */
    protected $sourceRoot;

    /**
     * @var string
     */
    protected $destinationRoot;

    /**
     * @param string $source
     * @param string $destination
     * @param string $sourceRoot
     * @param string $destinationRoot
     */
    public function __construct($source, $destination, $sourceRoot, $destinationRoot)
    {
        $this->source           = $source;
        $this->destination      = $destination;
        $this->sourceRoot       = $sourceRoot;
        $this->destinationRoot  = $destinationRoot;
    }

    /**
     * @return string
     */
    public function getRawSource()
    {
        return $this->source;
    }

    /**
     * @return string
     */
    public function getRawDestination()
    {
        return $this->destination;
    }

    /**
     * @return string
     */
    public function getSource()
    {
        return trim($this->normalizePath($this->source), '/');
    }

    /**
     * @return string
     */
    public function getDestination()
    {
        return trim($this->normalizePath($this->destination), '/');
    }

    /**
     * @return string
     */
    public function getAbsoluteSource()
    {
        return sprintf('%s/%s', $this->getSourceRoot(), $this->getSource());
    }

    /**
     * @return string
     */
    public function getAbsoluteDestination()
    {
        return sprintf('%s/%s', $this->getDestinationRoot(), $this->getDestination());
    }

    /**
     * @return string
     */
    public function getSourceRoot()
    {
        return rtrim($this->normalizePath($this->sourceRoot), '/');
    }

    /**
     * @return string
     */
    public function getDestinationRoot()
    {
        return rtrim($this->normalizePath($this->destinationRoot), '/');
    }

    /**
     * @param string $path
     * @return string
     */
    private function normalizePath($path)
    {
        return str_replace('\\', '/', $path);
    }
}

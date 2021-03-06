<?php

namespace Jh\MagentoComposerInstaller\Parser;

/**
 * Interface Parser
 * @package Jh\MagentoComposerInstaller\Magento\Parser
 */
interface ParserInterface
{
    /**
     * Return the mappings in an array:
     * array(
     *     array(source1, target1),
     *     array(source2, target2.1),
     *     array(source2, target2.2),
     *     array(source3, target3),
     *     ...
     * )
     *
     * @return array
     * @throws \ErrorException
     */
    public function getMappings();
}

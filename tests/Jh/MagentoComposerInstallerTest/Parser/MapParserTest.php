<?php

namespace Jh\MagentoComposerInstallerTest\Parser;

use Jh\MagentoComposerInstaller\Parser\MapParser;

/**
 * Class MapParserTest
 * @package Jh\MagentoComposerInstaller\Magento\Parser
 */
class MapParserTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @covers MagentoHackathon\Composer\Magento\Parser\MapParser::getMappings
     */
    public function testGetMappings()
    {
        $expected = [
            ['line/with/tab', 'record/one'],
            ['line/with/space', 'record/two'],
            ['line/with/space/and/tab', 'record/three']
        ];

        $parser = new MapParser($expected);

        $this->assertSame($expected, $parser->getMappings());
    }
}

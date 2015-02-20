<?php

namespace Jh\MagentoComposerInstallerTest\Parser;

use Composer\Test\TestCase;
use Composer\Config;
use Jh\MagentoComposerInstaller\Parser\MapParser;
use Jh\MagentoComposerInstaller\Parser\PathTranslationParser;

/**
 * Class PathMappingTranslationTest
 *
 * @package Jh\MagentoComposerInstallerTest\Parser
 */
class PathMappingTranslationTest extends \PHPUnit_Framework_TestCase
{

    public function testTranslate()
    {
        $mappings = [
            ['src/app/etc/modules/Example_Name.xml',   'app/etc/modules/Example_Name.xml'],
            ['src/app/code/community/Example/Name',    'app/code/community/Example/Name'],
            ['src/skin',                               'skin/frontend/default/default/examplename'],
            ['src/js',                                 'js/examplename'],
            ['src/media/images',                       'media/examplename_images'],
            ['src2/skin',                              './skin/frontend/default/default/examplename'],
            ['src2/js',                                './js/examplename'],
            ['src2/media/images',                      './media/examplename_images'],
        ];

        $translations = [
            'js/'       =>  'public/js/',
            'media/'    =>  'public/media/',
            'skin/'     =>  'public/skin/',
        ];

        $parser = new PathTranslationParser(new MapParser($mappings), $translations);

        $expected = [
            ['src/app/etc/modules/Example_Name.xml',   'app/etc/modules/Example_Name.xml'],
            ['src/app/code/community/Example/Name',    'app/code/community/Example/Name'],
            ['src/skin',                               'public/skin/frontend/default/default/examplename'],
            ['src/js',                                 'public/js/examplename'],
            ['src/media/images',                       'public/media/examplename_images'],
            ['src2/skin',                              'public/skin/frontend/default/default/examplename'],
            ['src2/js',                                'public/js/examplename'],
            ['src2/media/images',                      'public/media/examplename_images'],
        ];

        $this->assertEquals($expected, $parser->getMappings());
    }
}

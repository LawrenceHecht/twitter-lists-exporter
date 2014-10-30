<?php

namespace Codense\TwitterListsExport\Test;

use \Codense\TwitterListsExporter\Converter;

class ConverterTest extends \PHPUnit_Framework_TestCase
{

    protected $lists;
    protected $listsJson = '[{"name":"mylist","members":[{"screen_name":"alice"}]}]';
    protected $listsHtml = '<h3>mylist</h3><ul><li><a href="https://twitter.com/alice">alice</a></li></ul>';

    public function setUp()
    {
        $this->path = __DIR__ . '/temp/converted_output.tmp';

        $this->lists = array(
            (object) array(
                'name' => 'mylist',
                'members' => array(
                    (object) ['screen_name' => 'alice']
                )
            )
        );

    }

    /**
     * @expectedException Exception
     * @expectedExceptionMessage Invalid format
     */
    public function testInvalidFormat()
    {
        new Converter('invalid');
    }

    public function testSavingJson()
    {
        $converter = new Converter('json', $this->path);
        $converter->convert($this->lists);
        $this->assertEquals($this->listsJson, file_get_contents($this->path));
    }

    public function testSavingHtml()
    {
        $converter = new Converter('html', $this->path);
        $converter->convert($this->lists);
        $this->assertEquals($this->listsHtml, file_get_contents($this->path));
    }

}

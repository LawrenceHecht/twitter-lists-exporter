<?php

namespace Codense\TwitterListsExporter\Test;

use \Codense\TwitterListsExporter\Config;
use \Codense\TwitterListsExporter\ExporterCli;

class ExporterCliTest extends \PHPUnit_Framework_TestCase
{

    public function setUp()
    {
        $this->cli = new ExporterCli(['task_export.php']);
    }

    public function testParseValidArgs()
    {
        $this->cli->parseArgs([null, 'alice']);
        $this->assertEquals('alice', $this->cli->screenName);
        $this->assertEquals('all', $this->cli->listType);
        $this->assertEquals(Config::OUTPUT_PATH . 'alice.json', $this->cli->outputPath);
    }

    public function testParseValidArgsWithType()
    {
        $this->cli->parseArgs([null, 'bob', 'owned']);
        $this->assertEquals('bob', $this->cli->screenName);
        $this->assertEquals('owned', $this->cli->listType);
    }

    public function testParseValidWithExtraArgs()
    {
        $this->cli->parseArgs([null, 'CharlieBrown', 'all', 'whatever', '3123']);
        $this->assertEquals('CharlieBrown', $this->cli->screenName);
        $this->assertEquals('all', $this->cli->listType);
    }

    /**
     * @expectedException \Exception
     * @expectedExceptionMessage Invalid
     */
    public function testParseInvalidScreenName()
    {
        $this->cli->parseArgs([null, '*']);
    }

    /**
     * @expectedException \Exception
     * @expectedExceptionMessage Invalid
     */
    public function testParseEmptyScreenName()
    {
        $this->cli->parseArgs([null, '']);
    }

    /**
     * @expectedException \Exception
     * @expectedExceptionMessage Invalid
     */
    public function testParseInvalidListType()
    {
        $this->cli->parseArgs([null, 'alice', 'whatever']);
    }

}

<?php

namespace Codense\TwitterListsExport\Test;

use \TwitterOAuth\TwitterOAuth;
use \Codense\TwitterListsExporter\TwitterClient;

class TwitterClientTest extends \PHPUnit_Framework_TestCase
{

    protected $oauth;
    protected $client;

    public function setUp()
    {
        $this->oauth = $this->getMockBuilder('\TwitterOAuth\TwitterOAuth')
                            ->disableOriginalConstructor()
                            ->getMock();
        $this->client = new TwitterClient($this->oauth);
    }

    public function testGetAllLists()
    {
        $this->oauth->expects($this->once())
                           ->method('get')
                           ->with('lists/list', array('p1' => 3));
        $this->client->getLists('all', array('p1' => 3));
    }

    public function testGetOwnedLists()
    {
        $this->oauth->expects($this->once())
                           ->method('get')
                           ->with('lists/ownerships', array('p1' => 'something', 'p2' => 29));
        $this->client->getLists('owned', array('p1' => 'something', 'p2' => 29));
    }

    public function testGetSubscribedLists()
    {
        $this->oauth->expects($this->once())
                           ->method('get')
                           ->with('lists/subscriptions', array('p1' => '12', 'p2' => 32123));
        $this->client->getLists('subscribed', array('p1' => '12', 'p2' => 32123));
    }

    public function testGetListMembers()
    {
        $this->oauth->expects($this->once())
                           ->method('get')
                           ->with('lists/members', array('screen_name' => 'alice'));
        $this->client->getListMembers(array('screen_name' => 'alice'));
    }

    public function testCorrectApiPaths()
    {
        $this->assertEquals('lists/list', TwitterClient::getPath('all'));
        $this->assertEquals('lists/ownerships', TwitterClient::getPath('owned'));
        $this->assertEquals('lists/subscriptions', TwitterClient::getPath('subscribed'));
        $this->assertEquals('lists/members', TwitterClient::getPath('members'));
    }

    /**
     * @expectedException \Exception
     * @expectedExceptionMessage Invalid key
     */
    public function testInvalidApiPath()
    {
        TwitterClient::getPath('invalid');
    }

}

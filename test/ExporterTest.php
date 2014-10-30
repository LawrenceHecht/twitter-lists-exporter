<?php

namespace Codense\TwitterListsExport\Test;

use \Codense\TwitterListsExporter\Config;
use \Codense\TwitterListsExporter\Exporter;
use \Codense\TwitterListsExporter\Test\Faker;

class ExporterTest extends \PHPUnit_Framework_TestCase
{

    protected $exporter;

    public function setUp()
    {
        $twitterClient = $this->getMock('TwitterClient', ['getLists', 'getListMembers']);
        $twitterClient->method('getLists')
                      ->willReturn($this->getListsFromApi());
        $twitterClient->method('getListMembers')
                      ->will($this->onConsecutiveCalls(
                        $this->getListMembersFromApi(1),
                        $this->getListMembersFromApi(2)
                      ));
        $this->converter = $this->getMock('\Codense\TwitterListsExporter\Converter', ['convert'], ['json']);
        $this->exporter = new Exporter($twitterClient, $this->converter, true);
    }

    public function testExportLists()
    {
        $this->converter->expects($this->once())->method('convert')->with($this->getListsResults());
        $this->assertEquals($this->getListsResults(), $this->exporter->exportLists('alice'));
    }

    public function testExportListMembers()
    {
        $membersPage1 = (object) array(
            'users' => Faker::getListMembersFromApi(2),
            'next_cursor_str' => 111
        );
        $membersPage2 = (object) array(
            'users' => Faker::getListMembersFromApi(2),
            'next_cursor_str' => 222
        );
        $membersPage3 = (object) array(
            'users' => Faker::getListMembersFromApi(1)
        );
        $twitterClient = $this->getMock('TwitterClient', ['getListMembers']);
        $twitterClient->method('getListMembers')
                      ->will($this->onConsecutiveCalls($membersPage1, $membersPage2, $membersPage3));

        $twitterClient->expects($this->exactly(3))
                      ->method('getListMembers')
                      ->withConsecutive(
                        [$this->equalTo(['list_id' => '283', 'count' => Config::USERS_PER_PAGE, 'cursor' => -1])],
                        [$this->equalTo(['list_id' => '283', 'count' => Config::USERS_PER_PAGE, 'cursor' => 111])],
                        [$this->equalTo(['list_id' => '283', 'count' => Config::USERS_PER_PAGE, 'cursor' => 222])]
                      );

        $exporter = new Exporter($twitterClient, null, true);
        $members = $exporter->exportListMembers('283', -1);

        $this->assertEquals(5, count($members));
        $this->assertEquals($membersPage1->users[0]->id_str, $members[0]->id_str);
        $this->assertEquals($membersPage2->users[1]->screen_name, $members[3]->screen_name);
        $this->assertEquals($membersPage3->users[0]->name, $members[4]->name);
    }

    protected function getListsFromApi()
    {
        return array(
            (object) array(
                'id' => 22,
                'id_str' => '22',
                'name' => 'My Friends',
                'whatever' => '123',
                'user' => (object) array(
                    'id_str' => '77',
                    'screen_name' => 'JohnA',
                    'name' => 'John A.',
                    'language' => 'pt',
                    'lists_count' => 8
                )
            ),
            (object) array(
                'id' => 57,
                'id_str' => '57',
                'name' => 'news',
                'something' => 'else',
                'user' => (object) array(
                    'id_str' => '872',
                    'screen_name' => 'do1209301',
                    'background' => 'green',
                    'name' => 'dwoie doiw eoidjweojd',
                    'email' => 'a@a.com',
                    'language' => 'en'
                ),
            ),
        );
    }

    protected function getListsResults()
    {
        return array(
            (object) array(
                'id_str' => '22',
                'name' => 'My Friends',
                'owner' => (object) array(
                    'id_str' => '77',
                    'screen_name' => 'JohnA',
                    'name' => 'John A.'
                ),
                'members' => array(
                    (object) array(
                        'id_str' => '1',
                        'screen_name' => 'bob',
                        'name' => 'Bob'
                    ),
                    (object) array(
                        'id_str' => '2',
                        'screen_name' => 'cindy',
                        'name' => 'Cindy'
                    ),
                )
            ),
            (object) array(
                'id_str' => '57',
                'name' => 'news',
                'owner' => (object) array(
                    'id_str' => '872',
                    'screen_name' => 'do1209301',
                    'name' => 'dwoie doiw eoidjweojd'
                ),
                'members' => array(
                    (object) array(
                        'id_str' => '3',
                        'screen_name' => 'foxnews',
                        'name' => 'Fox News',
                    ),
                    (object) array(
                        'id_str' => '4',
                        'screen_name' => 'cnn',
                        'name' => 'CNN',
                    ),
                )
            ),
        );
    }

    protected function getListMembersFromApi($i)
    {
        if ($i === 1) {
            return (object) array(
                'users' => array(
                    (object) array(
                        'id' => 1,
                        'id_str' => '1',
                        'screen_name' => 'bob',
                        'name' => 'Bob',
                        'following_count' => 123
                    ),
                    (object) array(
                        'id' => 2,
                        'id_str' => '2',
                        'screen_name' => 'cindy',
                        'name' => 'Cindy',
                        'following_count' => 123
                    )
                )
            );
        } elseif ($i === 2) {
            return (object) array(
                'users' => array(
                    (object) array(
                        'id' => 3,
                        'id_str' => '3',
                        'language' => 'en',
                        'screen_name' => 'foxnews',
                        'name' => 'Fox News',
                        'created_at' => '2010-01-01',
                        'followers_count' => 1233421
                    ),
                    (object) array(
                        'id' => 4,
                        'id_str' => '4',
                        'background_color' => 'red',
                        'screen_name' => 'cnn',
                        'name' => 'CNN',
                        'following_count' => 4321,
                        'language' => 'en'
                    ),
                )
            );
        }
    }

}

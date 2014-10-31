<?php

namespace Codense\TwitterListsExporter;

class TwitterClient
{
    private $client;
    private $silent;

    public function __construct(\TwitterOAuth\TwitterOAuth $oauthClient, $silent = false)
    {
        $this->client = $oauthClient;
        $this->silent = $silent;
    }

    public static function getPath($key)
    {
        $paths = array(
            'all'        => 'lists/list',
            'owned'      => 'lists/ownerships',
            'subscribed' => 'lists/subscriptions',
            'members'    => 'lists/members'
        );
        if (array_key_exists($key, $paths)) {
            return $paths[$key];
        } else {
            throw new \Exception('Invalid key for path: ' . $key);
        }
    }

    public function getLists($listType, array $params)
    {
        $path = self::getPath($listType);
        $this->printStatus("Fetching lists...\n");

        return $this->client->get($path, $params);
    }

    public function getListMembers(array $params)
    {
        $path = self::getPath('members');
        $this->printStatus("Fetching list members...\n");

        return $this->client->get($path, $params);
    }

    protected function printStatus($status)
    {
        if (!$this->silent) {
            echo $status;
        }
    }

}

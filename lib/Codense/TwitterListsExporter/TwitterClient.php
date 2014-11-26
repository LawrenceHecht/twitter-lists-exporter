<?php

namespace Codense\TwitterListsExporter;

class TwitterClient
{
    private $client;
    private $logger;

    public function __construct(\TwitterOAuth\TwitterOAuth $oauthClient, LoggerInterface $logger = null)
    {
        $this->client = $oauthClient;
        $this->logger = $logger ?: new Blackhole();
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
        $this->logger->info("Fetching lists...\n");

        return $this->client->get($path, $params);
    }

    public function getListMembers(array $params)
    {
        $path = self::getPath('members');
        $this->logger->info("Fetching list members...\n");

        return $this->client->get($path, $params);
    }

}

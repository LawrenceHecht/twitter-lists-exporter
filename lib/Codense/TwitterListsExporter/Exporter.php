<?php

namespace Codense\TwitterListsExporter;

class Exporter
{
    private $twitterClient;
    private $converter;
    private $silent;

    public function __construct(TwitterClient $twitterClient, Converter $converter = null, $silent = false)
    {
        $this->twitterClient = $twitterClient;
        $this->converter     = $converter;
        $this->silent        = $silent;
    }

    public function exportLists($screenName, $listType = 'all')
    {
        $response = $this->twitterClient->getLists($listType, ['screen_name' => $screenName]);
        $lists = [];
        if (isset($response->lists) && is_array($response->lists)) {
            $response = $response->lists;
        }
        if (is_array($response)) {
            foreach ($response as $list) {
                $lists[] = (object) array(
                    'id_str'  => $list->id_str,
                    'name'    => $list->name,
                    'owner'   => $this->getUser($list->user),
                    'members' => $this->exportListMembers($list->id_str, -1)
                );
            }
        } else {
            throw new \Exception("Invalid response: \n\n" . var_export($response, true));
        }

        if (is_a($this->converter, Converter::class)) {
            $this->printStatus('Saving lists...');
            $this->converter->convert($lists);
        }
        $this->printStatus("\nNumber of lists exported: " . count($lists) . "\n");

        return $lists;
    }

    public function exportListMembers($listId, $cursor)
    {
        $members = [];
        $params = array(
            'list_id' => $listId,
            'count'   => Config::USERS_PER_PAGE,
            'cursor'  => $cursor
        );
        $response = $this->twitterClient->getListMembers($params);
        if (isset($response->users) && is_array($response->users)) {
            foreach ($response->users as $user) {
                $members[] = $this->getUser($user);
            }
            if (!empty($response->next_cursor_str)) {
                $members = array_merge($members, $this->exportListMembers($listId, $response->next_cursor_str));
            }
        } else {
            throw new \Exception("Invalid response: \n\n" . var_export($response, true));
        }

        return $members;
    }

    private function getUser($user)
    {
        return (object) [
            'id_str'      => $user->id_str,
            'screen_name' => $user->screen_name,
            'name'        => $user->name
        ];
    }

    private function printStatus($status)
    {
        if (!$this->silent) {
            echo $status;
        }
    }

}

<?php

namespace Codense\TwitterListsExporter;

class ExporterCli
{

    public $logger;
    public $screenName;
    public $listType;
    public $outputPath;

    public function __construct(array $args, LoggerInterface $logger = null)
    {
        $this->logger = $logger ?: new Blackhole();
        $this->parseArgs($args);
    }

    public function parseArgs($args)
    {
        if (count($args) < 2) {
            $this->logger->warning("\nUsage: php {$args[0]} twitter_username [owned | subscribed]\n\n");
        } else {
            $this->screenName = trim($args[1]);
            $this->listType = (!empty($args[2]) ? trim($args[2]) : 'all');
            $screenNameIsInvalid = empty($this->screenName) || preg_match('/[^\w]/', $this->screenName);
            if ($screenNameIsInvalid || !TwitterClient::getPath($this->listType)) {
                throw new \Exception("Invalid arguments: \n\n" . var_export($args, true));
            } else {
                $this->outputPath = Config::OUTPUT_PATH . "$this->screenName." . Config::FORMAT;
            }
        }
    }

    public function run()
    {
        if ($this->screenName && $this->listType) {
            $logger = new StdoutLogger();
            $oauthClient = new \TwitterOAuth\TwitterOAuth(Config::getTwitter());
            $twitterClient = new TwitterClient($oauthClient, $logger);
            $converter = new Converter(Config::FORMAT, $this->outputPath);

            $exporter = new Exporter($twitterClient, $converter, $logger);
            $exporter->exportLists($this->screenName, $this->listType);
        } else {
            $this->logger->error("Cannot run without arguments.\n\n");
        }
    }

}

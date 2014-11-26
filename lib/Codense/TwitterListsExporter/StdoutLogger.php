<?php

namespace Codense\TwitterListsExporter;

class StdoutLogger implements LoggerInterface
{
    public function error($message)
    {
        echo $message;
    }
    public function warning($message)
    {
        echo $message;
    }
    public function info($message)
    {
        echo $message;
    }
}

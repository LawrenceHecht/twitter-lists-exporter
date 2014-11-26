<?php

namespace Codense\TwitterListsExporter;

interface LoggerInterface
{
    public function error($message);
    public function warning($message);
    public function info($message);
}

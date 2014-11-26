<?php

namespace Codense\TwitterListsExporter;

require_once __DIR__ . '/config.php';

$cli = new ExporterCli($argv, new StdoutLogger());
$cli->run();

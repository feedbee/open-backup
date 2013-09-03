<?php

$options = require "get-cmd-opts.php";

require "vendor/autoload.php";

$al = new Zend\Loader\StandardAutoloader(array(
	'namespaces' => array(
		'OpenBackup' => __DIR__ . '/lib/OpenBackup'
	),
));
$al->register();

define('VERBOSE', true);
define('LOG_LEVEL', LOG_DEBUG);
require_once 'set-logger.php';
Log::debug('Start processing');

Log::debug("Read config");
$config = Zend\Config\Factory::fromFile('configs/test.json', true);
Log::info("Config name is `{$config->about->name}`. Author: {$config->about->author}");

foreach ($config->snapshots as $snapshot)
{
	if (!$snapshot->enabled)
	{
		continue;
	}
	Log::info("Snapshot name is `{$snapshot->title}`");

	Log::debug("Setup dispatcher");
	$dispatcher = new \OpenBackup\Dispatcher();
	$dispatcher->setBasePath($snapshot->basePath);
	$dispatcher->setFilename($snapshot->filename);
	$dispatcher->addActions($snapshot->controllers);
	$dispatcher->setOptions($snapshot->options);
	Log::debug("Run dispatcherization");
	$exitCode = $dispatcher->dispatch($options['mode']);

	Log::info("Exit code: " . $exitCode . "\n");
}
<?php

$opts = getopt("hm:", array(
	'help',
	'mode:',
));

if (isset($opts['h']) || isset($opts['help']))
{
	print 'Usage: php backup.php [-m|--mode backup|restore]' . PHP_EOL;
	exit;
}

$options = array();
if (isset($opts['m']))
{
	$options['mode'] = $opts['m'];
}
else if (isset($opts['mode']))
{
	$options['mode'] = $opts['mode'];
}

if (!isset($options['mode']))
{
	$options['mode'] = 'backup';
}
else if (!in_array($options['mode'], array(
	'backup',
	'restore',
)))
{
	die("Invalid value set for `mode` parameter: {$options['mode']}. Use --help for usage information" . PHP_EOL);
}

return $options;
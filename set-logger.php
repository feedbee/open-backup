<?php

/**
 * Class Log
 *
 * @method static void emerg($message, $extras = null)
 * @method static void alert($message, $extras = null)
 * @method static void crit($message, $extras = null)
 * @method static void err($message, $extras = null)
 * @method static void warn($message, $extras = null)
 * @method static void notice($message, $extras = null)
 * @method static void info($message, $extras = null)
 * @method static void debug($message, $extras = null)
 */
class Log
{
	/**
	 * @var Zend\Log\Logger
	 */
	static $logger = null;
	/**
	 * @return Zend\Log\Logger
	 */
	static function logger()
	{
		return self::$logger;
	}
	static function __callStatic($name, $arguments)
	{
		call_user_func_array(array(self::$logger, $name), $arguments);
	}
}

$loggerConfig = array();

if (defined('VERBOSE') && VERBOSE)
{
	if (!isset($loggerConfig['writers']) || !is_array($loggerConfig['writers']))
	{
		$loggerConfig['writers'] = array();
	}
	$loggerConfig['writers'][] = array(
		'name' => 'Stream',
		'options' => array(
			'stream' => 'php://output',
			'filters' => array(
				array(
					'name' => 'Priority',
					'options' => array(
						'priority' => LOG_LEVEL,
					),
				),
			),
			'formatter' => array(
				'name' => 'Simple',
				'options' => array(
					'dateTimeFormat' => 'H:i:s', // Y-m-d H:i:s
				),
			),
		),
    );
}
Log::$logger = new Zend\Log\Logger($loggerConfig);


// -- Exceptions Handling --
set_exception_handler(function(Exception $exception){
	\Log::err('Uncaught exception thrown:' . PHP_EOL . $exception->__toString());
	die("Uncaught exception thrown. Checkout error log for details." . PHP_EOL);
});
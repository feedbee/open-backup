<?php

namespace OpenBackup\Job\Command;

use OpenBackup\Dispatcher;

class Delete implements Iface
{
	private $_mode = false;
	
	const MODE_BACKUP = Dispatcher::MODE_BACKUP;
	const MODE_RESTORE = Dispatcher::MODE_BACKUP;
	const MODE_BOTH = 'both';
	public function __construct($mode = self::MODE_BOTH)
	{
		$this->_mode = $mode;
	}
	
	public function getPackCmd($sourcePath, $destPath)
	{
		return 'rm -R ' . escapeshellarg($sourcePath);
	}
	
	public function getUnpackCmd($sourcePath, $destPath)
	{
		throw new \Exception('Not yet implemented');
	}
	
	public function getPackedFilename($filename)
	{
		return '';
	}
}
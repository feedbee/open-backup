<?php

namespace OpenBackup\Job\Command;

class Gzip implements Iface
{
	private $_removeSource = false;
	
	public function __construct($removeSource = false)
	{
		$this->_removeSource = $removeSource;
	}
	
	public function getPackCmd($sourcePath, $destPath)
	{
		$source = '';
		if (!is_null($sourcePath))
		{
			$source = ' ' . ($this->_removeSource ? '' : '-c ')
					. escapeshellarg($sourcePath);
		}
		$dest = '';
		if (!is_null($destPath))
		{
			$dest = ' > ' . escapeshellarg($destPath);
		}
		
		return "gzip{$source}{$dest}";
	}
	
	public function getUnpackCmd($sourcePath, $destPath)
	{
		$source = '';
		if (!is_null($sourcePath))
		{
			$source = ' ' . ($this->_removeSource ? '' : '-c ')
					. escapeshellarg($sourcePath);
		}
		$dest = '';
		if (!is_null($destPath))
		{
			$dest = ' > ' . escapeshellarg($destPath);
		}
		
		return "gzip -d{$source}{$dest}";
	}
	
	public function getPackedFilename($filename)
	{
		return "{$filename}.gz";
	}
}
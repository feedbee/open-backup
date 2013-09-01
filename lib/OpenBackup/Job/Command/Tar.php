<?php

namespace OpenBackup\Job\Command;

class Tar implements Iface
{
	private $_gzip = false;
	private $_removeSource = false;
	
	public function __construct($gzip = false, $removeSource = false)
	{
		$this->_gzip = $gzip;
		$this->_removeSource = $removeSource;
	}
	
	public function getPackCmd($sourcePath, $destPath)
	{
		$options = 'c';
		$options .= $this->_gzip ? 'z' : '';
		
		if (is_null($sourcePath))
		{
			throw new \Exception('Tar does\'n support stdin in pack mode');
		}
		$source = escapeshellarg($sourcePath);
		
		$dest = '';
		if (!is_null($destPath))
		{
			$dest = " " . escapeshellarg($destPath);
			$options .= 'f';
		}
		
		if ($this->_removeSource)
		{
			$options = '-remove-files -' . $options;
		}
		
		return "tar -{$options}{$dest} {$source}";
	}
	
	public function getUnpackCmd($sourcePath, $destPath)
	{
		$options = 'x';
		$options .= $this->_gzip ? 'z' : '';

		$source = '';
		if (!is_null($sourcePath))
		{
			$source = ' ' . escapeshellarg($sourcePath);
		}

		// $dest = ''; Destination path saved in archive structure in backup time
		if (!is_null($destPath))
		{
			//$dest = " " . escapeshellarg($destPath);
			$options .= 'f';
		}

		if ($this->_removeSource)
		{
			$options = '-remove-files -' . $options;
		}

		return "tar -{$options}{$source}"; // {$dest}
	}
	
	public function getPackedFilename($filename)
	{
		return "{$filename}.tar" . ($this->_gzip ? '.gz' : '');
	}
}
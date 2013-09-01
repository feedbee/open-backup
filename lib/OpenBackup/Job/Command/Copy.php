<?php

namespace OpenBackup\Job\Command;

class Copy implements Iface
{
	public function getPackCmd($sourcePath, $destPath)
	{
		return 'cp -R ' . escapeshellarg($sourcePath) . ' ' . escapeshellarg($destPath);
	}
	
	public function getUnpackCmd($sourcePath, $destPath)
	{
		throw new \Exception('Not yet implemented');
	}
	
	public function getPackedFilename($filename)
	{
		return $filename;
	}
}
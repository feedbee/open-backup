<?php

namespace OpenBackup\Job\Command\Chain;
use OpenBackup\Job\Command;

class Pipe extends AbstractBase
{
	const PIPE_DELIMITER = ' | ';
	
	public function getPackCmd($sourcePath, $destPath)
	{
		$source = $sourcePath;
		$dest = null;
		
		$commands = $this->getCommands();
		
		$cmdArr = array();
		for ($i = 0; $i < count($commands); $i++)
		{
			$command = $commands[$i];
			/* @var \OpenBackup\Job\Command\Iface $command */
			if ($i == count($commands) - 1)
			{
				$dest = $destPath;
			}
			$cmdArr[] = $command->getPackCmd($source, $dest);
			$source = null;
		}
		
		return implode(self::PIPE_DELIMITER, $cmdArr);
	}
	
	public function getUnpackCmd($sourcePath, $destPath)
	{
		throw new \Exception('Not yet implemented');
	}
	
	public function getPackedFilename($filename)
	{
		foreach ($this->getCommands() as $command)
		{
			$filename = $command->getPackedFilename($filename);
		}
		return $filename;
	}
}
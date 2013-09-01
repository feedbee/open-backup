<?php

namespace OpenBackup\Controller;

use OpenBackup\Job\Command;
use OpenBackup\Job;

class FileSystem extends AbstractBase
{
	public function backup($path)
	{
		$tar = $this->_getParameter('tar');
		$gzip = $this->_getParameter('gzip');
		$filename =  $this->_getParameter('filename');
		
		if (empty($filename))
		{
			$filename = date('Y-m-d_H-i-s');
			if ($tar)
			{
				$filename .= '.tar';
			}
			if ($gzip)
			{
				$filename .= '.gz';
			}
		}
		
		$sourcePath = $this->_getParameter('path');
		$destPath = $path . DIRECTORY_SEPARATOR . $filename;
		
		if (!$tar && !$gzip)
		{
			$command = new Command\Copy;
		}
		else if (!$tar && $gzip)
		{
			$command = new Command\Gzip;
		}
		else
		{
			$command = new Command\Tar(!!$gzip);
		}
		
		$task = new Job\Task($command, $sourcePath, $destPath);
		
		$job = new Job\Job(Job\Job::MODE_BACKUP);
		$job->addTask($task);
		
		return $this->getExecutor()->execute($job->getCommands());
	}
	
	public function restore($path)
	{
		$tar = $this->_getParameter('tar');
		$gzip = $this->_getParameter('gzip');
		$filename =  $this->_getParameter('filename');
		
		if (empty($filename))
		{
			$filename = date('Y-m-d_H-i-s');
			if ($tar)
			{
				$filename .= '.tar';
			}
			if ($gzip)
			{
				$filename .= '.gz';
			}
		}
		
		$sourcePath = $this->_getParameter('path');
		$destPath = $path . DIRECTORY_SEPARATOR . $filename;
		
		if (!$tar && !$gzip)
		{
			$command = new Command\Copy;
		}
		else if (!$tar && $gzip)
		{
			$command = new Command\Gzip;
		}
		else
		{
			$command = new Command\Tar(!!$gzip);
		}
		
		$task = new Job\Task($command, $sourcePath, $destPath);
		
		$job = new Job\Job(Job\Job::MODE_RESTORE);
		$job->addTask($task);
		
		return $this->getExecutor()->execute($job->getCommands());
	}
}
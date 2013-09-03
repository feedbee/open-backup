<?php

namespace OpenBackup\Controller;

use OpenBackup\Job\Command\Chain\Pipe;
use OpenBackup\Job\Command\Gzip;
use OpenBackup\Job\Command\Mysqldump;
use OpenBackup\Job\Job;
use OpenBackup\Job\Task;

class Database extends AbstractBase
{
	public function backup($path)
	{
		$url = $this->_getParameter('url');
		$gzip = $this->_getParameter('gzip');
		$filename =  $this->_getParameter('filename');
		
		if (empty($filename))
		{
			$filename = date('Y-m-d_H-i-s') . '.sql';
			if ($gzip)
			{
				$filename .= '.gz';
			}
		}
		
		$destPath = $path . DIRECTORY_SEPARATOR . $filename;
		
		$dumpCommand = new Mysqldump;
		
		if (!$gzip)
		{
			$targetCommand = $dumpCommand;
		}
		else
		{
			$gzipCommand = new Gzip;
			
			$pipe = new Pipe;
			$pipe->addCommand($dumpCommand);
			$pipe->addCommand($gzipCommand);
			
			$targetCommand = $pipe;
		}
		
		$task = new Task($targetCommand, $url, $destPath);
		
		$job = new Job(Job::MODE_BACKUP);
		$job->addTask($task);
		
		return $this->getExecutor()->execute($job->getCommands());
	}
	
	public function restore($path)
	{
		$url = $this->_getParameter('url');
		$gzip = $this->_getParameter('gzip');
		$filename =  $this->_getParameter('filename');

		if (empty($filename))
		{
			$filename = date('Y-m-d_H-i-s') . '.sql';
			if ($gzip)
			{
				$filename .= '.gz';
			}
		}

		$sourcePath = $path . DIRECTORY_SEPARATOR . $filename;

		$restoreCommand = new Mysqldump;

		if (!$gzip)
		{
			$targetCommand = $restoreCommand;
		}
		else
		{
			$gzipCommand = new Gzip;

			$pipe = new Pipe;
			$pipe->addCommand($gzipCommand);
			$pipe->addCommand($restoreCommand);

			$targetCommand = $pipe;
		}

		$task = new Task($targetCommand, $url, $sourcePath);

		$job = new Job(Job::MODE_RESTORE);
		$job->addTask($task);

		return $this->getExecutor()->execute($job->getCommands());
	}
}
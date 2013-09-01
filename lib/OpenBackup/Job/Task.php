<?php

namespace OpenBackup\Job;

class Task
{
	private $_command;
	private $_sourcePath;
	private $_destPath;
	
	public function __construct(Command\Iface $command, $sourcePath, $destPath)
	{
		$this->_command = $command;
		$this->_sourcePath = $sourcePath;
		$this->_destPath = $destPath;
	}
	
	public function getCommandString($mode)
	{
		if ($mode == Job::MODE_BACKUP)
		{
			return $this->_getPackCmd();
		}
		else
		{
			return $this->_getUnpackCmd();
		}
	}
	
	private function _getPackCmd()
	{
		return $this->_command->getPackCmd($this->_sourcePath, $this->_destPath);
	}
	
	private function _getUnpackCmd()
	{
		return $this->_command->getUnpackCmd($this->_destPath, $this->_sourcePath);
	}
	
	
	public function getCommand()
	{
		return $this->_command;
	}
	public function setCommand($command)
	{
		$this->_command = $command;
	}

	public function getSourcePath()
	{
		return $this->_sourcePath;
	}
	public function setSourcePath($sourcePath)
	{
		$this->_sourcePath = $sourcePath;
	}

	public function getDestPath()
	{
		return $this->_destPath;
	}
	public function setDestPath($destPath)
	{
		$this->_destPath = $destPath;
	}
}
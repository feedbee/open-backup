<?php

namespace OpenBackup\Job;

use OpenBackup\Dispatcher;

class Job
{
	private $_mode;	
		const MODE_BACKUP = Dispatcher::MODE_BACKUP;
	const MODE_RESTORE = Dispatcher::MODE_RESTORE;

	/**
	 * @var \OpenBackup\Job\Task[]
	 */
	private $_tasks = array();
	
	public function __construct($mode)
	{
		$this->_mode = $mode;
	}
	
	public function addTask(Task $task)
	{
		$this->_tasks[] = $task;
	}
	
	public function getCommands()
	{
		$tasks = $this->_mode == self::MODE_RESTORE
				? array_reverse($this->_tasks) : $this->_tasks;
		
		$allCommands = array();
		foreach ($tasks as $task)
		{
			$allCommands[] = $task->getCommandString($this->_mode);
		}
		
		return implode(';', $allCommands);
	}
	
	public function getMode()
	{
		return $this->_mode;
	}
	public function setMode($mode)
	{
		$this->_mode = $mode;
	}
}
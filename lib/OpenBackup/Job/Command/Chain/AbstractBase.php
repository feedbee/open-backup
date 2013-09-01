<?php

namespace OpenBackup\Job\Command\Chain;
use OpenBackup\Job\Command;

abstract class AbstractBase implements Iface
{
	/**
	 * @var \OpenBackup\Job\Command\Iface[]
	 */
	private $_commands = array();
	
	public function addCommand(Command\Iface $command)
	{
		$this->_commands[] = $command;
	}
	
	public function getCommands()
	{
		return $this->_commands;
	}
	public function setCommands($commands)
	{
		$this->_commands = $commands;
	}
}
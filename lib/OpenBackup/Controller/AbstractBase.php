<?php

namespace OpenBackup\Controller;

abstract class AbstractBase implements Iface
{
	private $_config;
	/**
	 * @var \OpenBackup\Execute\Shell
	 */
	private $_executor;
	
	public function getParameters()
	{
		return $this->_config;
	}
	public function setParameters($config)
	{
		$this->_config = $config;
	}
	
	public function getExecutor()
	{
		return $this->_executor;
	}
	public function setExecutor($executor)
	{
		$this->_executor = $executor;
	}
		
	protected function _getParameter($name)
	{
		if (!isset($this->_config))
		{
			throw new \Exception('Config is not set');
		}
		
		if (isset($this->_config->$name))
		{
			return $this->_config->$name;
		}
		
		return null;
	}
}
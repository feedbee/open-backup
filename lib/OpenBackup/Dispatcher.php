<?php

namespace OpenBackup;

class Dispatcher
{
	private $_actions = array();
	private $_basePath;
	private $_filename;
	
	private $_tar = false;
	private $_gzip = false;
	
	private $_plugins = array(
		'preBackup' => array(),
		'preRestore' => array(),
		'postBackup' => array(),
		'postRestore' => array()
	);
	
	const MODE_BACKUP = 'backup';
	const MODE_RESTORE = 'restore';
	public function dispatch($mode)
	{
		\Log::debug("Dispatch process started in $mode mode");
		
		$this->_preDispatch($mode);
		$result = $this->_runDispatchLoop($mode);
		$this->_postDispatch($mode);
		
		return $result;
	}
	
	private function _runDispatchLoop($mode)
	{
		\Log::debug("Dispatch process started");
		$result = 0;
		$i = 0;
		foreach ($this->_actions as $action)
		{
			$name = isset($action['name']) ? $action['name'] : '-';
			$name .= " (#$i)";
			\Log::debug("Action $name");
			
			$path = $this->_basePath
					. (!empty($this->_filename) ? DIRECTORY_SEPARATOR . $this->_filename : '')
					. (!is_null($action['section']) ? DIRECTORY_SEPARATOR . $action['section'] : '');
			if (!is_dir($path))
			{
				mkdir($path, 0777, true);
			}
			$controller = $action['controller'];
			/* @var \OpenBackup\Controller\Iface $controller */
			
			$executor = $action['executor'];
			if (is_null($executor))
			{
				$executor = 'Shell';
			}
			$executorClassName = "\\OpenBackup\\Execute\\{$executor}";
			$executorInstance = new $executorClassName();
			$controller->setExecutor($executorInstance);
			
			if ($mode == self::MODE_BACKUP)
			{
				$result += $controller->backup($path);
			}
			else
			{
				$result += $controller->restore($path);
			}
			$i++;
		}
		
		return $result > 0 ? 1: 0;
	}
	
	private function _preDispatch($mode)
	{
		\Log::debug("preDispatch started");
		
		$preCommands = $this->_getPlugins("pre", $mode);
		
		if ($mode == self::MODE_RESTORE)
		{
			if ($this->_tar || $this->_gzip)
			{
				if ($this->_tar)
				{
					array_unshift($preCommands, new Job\Command\Tar((bool)$this->_gzip));
				}
				else if ($this->_gzip)
				{
					array_unshift($preCommands, new Job\Command\Gzip(false));
				}
			}
		}
		
		if (count($preCommands) > 0)
		{
			$preJob = $this->_getPluginsJob($preCommands, $mode);
			$exitCode = $preJob();
			\Log::debug("preDispatch finished: " . count($preCommands) . " commands was executed (exit code $exitCode)");
			
			return $exitCode;
		}
		
		\Log::debug("preDispatch finished: no commands was executed");
		
		return 0;
	}
	
	private function _postDispatch($mode)
	{
		\Log::debug("postDispatch started");
		
		$postCommands = $this->_getPlugins("post", $mode);
		
		if ($mode == self::MODE_BACKUP)
		{
			if ($this->_tar || $this->_gzip)
			{
				if ($this->_tar)
				{
					array_unshift($postCommands, new Job\Command\Tar((bool)$this->_gzip, true));
				}
				else if ($this->_gzip)
				{
					array_unshift($postCommands, new Job\Command\Gzip(true));
				}
			}
		}
		
		if (count($postCommands) > 0)
		{
			$preJob = $this->_getPluginsJob($postCommands, $mode);
			$exitCode = $preJob();
			\Log::debug("postDispatch finished: " . count($postCommands) . " commands was executed (exit code $exitCode)");
			
			return $exitCode;
		}
		
		\Log::debug("postDispatch finished: no commands was executed");
		
		return 0;
	}
	
	private function _getPlugins($prefix, $mode)
	{
		$modeKey = ($mode == self::MODE_BACKUP ? 'Backup' : 'Restore');
		return $this->_plugins["$prefix$modeKey"];
	}
	
	private function _getPluginsJob($commands, $mode)
	{
		if (count($commands) > 0)
		{
			$_basePath = $this->_basePath;
			$filename = $this->_filename;
			
			$job = new Job\Job($mode);
			if (empty($filename))
			{
				throw new \Exception('Using options requires to set Snapshot Filename');
			}
			foreach ($commands as $command)
			{
				/* @var \OpenBackup\Job\Command\Iface $command */
				$task = new Job\Task($command, $_basePath . DIRECTORY_SEPARATOR . $filename,
						$_basePath . DIRECTORY_SEPARATOR . ($packedFilename = $command->getPackedFilename($filename)));
				$job->addTask($task);
				$filename = $packedFilename;
			}

			$plugin = function() use ($job)
			{
				$executor = new Execute\Shell;
				return $executor->execute($job->getCommands());
			};

			return $plugin;
		}
		
		return null;
	}
	
	public function addActions($actionsConfig)
	{
		foreach ($actionsConfig as $controllerConfig)
		{
			$controllerClassName = "\\OpenBackup\\Controller\\{$controllerConfig->controller}";
			$controller = new $controllerClassName;
			/* @var \OpenBackup\Controller\Iface $controller */
			$controller->setParameters($controllerConfig->parameters);
			$this->_actions[] = array(
				'section' => $controllerConfig->section,
				'controller' => $controller,
				'executor' => $controllerConfig->executor
			);
		}
	}
	
	public function setOptions($config)
	{
		$this->_tar = (bool)$config->tar;
		$this->_gzip = (bool)$config->gzip;
	}
	
	public function setBasePath($path)
	{
		$this->_basePath = $path;
	}
	public function getBasePath()
	{
		return $this->_basePath;
	}
	
	public function getFilename()
	{
		return $this->_filename;
	}
	public function setFilename($filename)
	{
		$this->_filename = $filename;
	}
}
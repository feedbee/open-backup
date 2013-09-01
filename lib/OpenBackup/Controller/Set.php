<?php

namespace OpenBackup\Controller;

use OpenBackup\Dispatcher;

class Set extends AbstractBase
{
	private $_dispatcher;

	public function backup($path)
	{
		return $this->_do($path, Dispatcher::MODE_BACKUP);
	}
	
	public function restore($path)
	{
		return $this->_do($path, Dispatcher::MODE_RESTORE);
	}

	private function _do($path, $mode)
	{
		$dispatcher = $this->_getDispatcher();
		$dispatcher->setBasePath($path);

		return $dispatcher->dispatch($mode);
	}

	private function _getDispatcher()
	{
		if (is_null($this->_dispatcher))
		{
			$this->_dispatcher = new Dispatcher();
			$this->_dispatcher->addActions($this->getParameters());
		}

		return $this->_dispatcher;
	}
}
<?php

namespace OpenBackup\Controller;

interface Iface
{
	public function setParameters($config);
	public function getParameters();
	
	public function getExecutor();
	public function setExecutor($executor);
	
	public function backup($path);
	public function restore($path);
}
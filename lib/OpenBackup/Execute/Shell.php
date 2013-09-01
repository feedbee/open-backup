<?php

namespace OpenBackup\Execute;

class Shell
{
	public function execute($cmd)
	{
		//print("[# $cmd]\n");
		\Log::info("[# $cmd]");
		return `$cmd`;
	}
}
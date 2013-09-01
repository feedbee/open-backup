<?php

namespace OpenBackup\Job\Command\Chain;
use OpenBackup\Job\Command;

interface Iface extends Command\Iface
{
	public function addCommand(Command\Iface $command);
}
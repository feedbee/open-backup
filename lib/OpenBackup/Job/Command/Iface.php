<?php

namespace OpenBackup\Job\Command;

interface Iface
{
	public function getPackCmd($sourcePath, $destPath);
	public function getUnpackCmd($sourcePath, $destPath);
	
	public function getPackedFilename($filename);
}
<?php

namespace OpenBackup\Job\Command;

class Mysqldump implements Iface
{
	public function getPackCmd($sourcePath, $destPath)
	{
		if (is_null($sourcePath))
		{
			throw new \Exception('Mysqldump does\'n support stdin in pack mode');
		}
		$dest = '';
		if (!is_null($destPath))
		{
			$dest = " > " . escapeshellarg($destPath);
		}
		
		$matches = null;
		preg_match('#^mysql://(.+)(:(.+))?@(.+)(:(.+))?/(.*)$#isU', $sourcePath, $matches);
		$user = $matches[1];
		$password = $matches[3];
		$host = $matches[4];
		$port = $matches[6];
		$database = $matches[7];
		
		return "mysqldump -h {$host} -P {$port} -u {$user} -p{$password} {$database}{$dest}";
	}
	
	public function getUnpackCmd($sourcePath, $destPath)
	{
		if (is_null($destPath))
		{
			throw new \Exception('Mysql does\'n support stdout in restore mode');
		}
		$source = '';
		if (!is_null($sourcePath))
		{
			$source = " < " . escapeshellarg($sourcePath);
		}

		$matches = null;
		preg_match('#^mysql://(.+)(:(.+))?@(.+)(:(.+))?/(.*)$#isU', $destPath, $matches);
		$user = $matches[1];
		$password = $matches[3];
		$host = $matches[4];
		$port = $matches[6];
		$database = $matches[7];

		return "mysql -h {$host} -P {$port} -u {$user} -p{$password} {$database}{$source}";
	}
	
	public function getPackedFilename($filename)
	{
		return "{$filename}.sql";
	}
}
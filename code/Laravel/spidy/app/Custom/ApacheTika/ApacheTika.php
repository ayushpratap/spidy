<?php

namespace App\Custom\ApacheTika;

use App\Custom\ApacheTika\Modes\ServerMode;
/**
*
*/
abstract class ApacheTika
{
	protected $cache = [];
	public static function make()
	{
		return new ServerMode();
	}

	public function getText($file)
	{
		return $this->request($file,'text');
	}
	public function getMetaData($file)
	{
		return $this->request($file,'meta');
	}
	abstract protected function request($file, $option);
}

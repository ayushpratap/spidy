<?php
namespace App\Custom\ApacheTika\MetaData;

use Exceptions;

abstract class MetaData{
	public $mime = null;
	public $created = null;
	public $updated = null;
	public $meta = [];

	public function __construct($meta , $value)
	{
		$this->meta = $meta;

		foreach ($this->meta as $key => $value) {
			$this->setAttribute($key,$value);
		}
		if(empty($this->updated))
		{
			$this->updated = $this->created;
		}
	}

	public static function make($response, $file)
	{
		$meta = json_decode($response);
		if(json_last_error())
		{
			throw new Exception(json_last_error_msg());
		}
		if(empty($meta))
		{
			throw new Exception("Empty Response");
		}
		if(is_array($meta->{'Content-Type'}))
		{
			$mime = current($meta->{'Content-Type'});
		}
		else
		{
			$mime = $meta->{'Content-Type'};
		}

		switch (current(explode('/', $mime))) 
		{
			case 'image':
				$instance = new ImageMetaData($meta , $file);
				break;

			default:
				$instance = new DocumentMetaData($meta , $file);
				break;
		}

		return $instance;
	}
	abstract protected function setAttribute($key, $value);
}
?>
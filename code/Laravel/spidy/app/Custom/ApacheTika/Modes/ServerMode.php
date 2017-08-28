<?php

namespace App\Custom\ApacheTika\Modes;

use App\Custom\ApacheTika\ApacheTika;
use App\Custom\ApacheTika\MetaData\MetaData;
use Exception;

/**
*
*/
class ServerMode extends ApacheTika
{
	protected $cache = [];
	//protected $path = null;
	protected $host = '127.0.0.1';
	protected $port = '12345';
	//protected $isServerRunning = FALSE;
	private $_timeout = 50;

	//function __construct($tikaPath , $tikaHost, $tikaPort) : old line
	function __construct()
	{
		$url = "http://".$this->host.":".$this->port."/tika";
		$this->run([
				CURLOPT_TIMEOUT =>1,
				CURLOPT_URL => $url,
			]);
		//if(!($this->isServerRunning))
		//{
			/*$this->path = $tikaPath;
			$this->host = $tikaHost;
			$this->port = $tikaPort;
			*/
		//}
	}

	protected function run(array $options = [])
	{
		// init cURL and options
		$curl = curl_init();
		curl_setopt_array($curl,[
			CURLINFO_HEADER_OUT => true,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_TIMEOUT => $this->_timeout,
			]+$options);

		//Get the response code
		$response = [
			trim(curl_exec($curl)),
			curl_getinfo($curl,CURLINFO_HTTP_CODE),
		];

		// Throw exception of the request fails.
		if(curl_errno($curl))
		{
			throw new Exception(curl_error($curl), curl_errno($curl));
		}

		return $response;
	}

	public function show()
	{
		echo 'Tika Host : '.$this->host.'<br/>Tika Port : ',$this->port;
	}

	protected function request($file,$type)
	{
		$headers = [];
		switch($type)
		{
			case 'meta':
						$resource = 'meta';
						$headers[] = 'Accept: application/json';
						break;
			case 'text':
						$resource = 'tika';
						$headers[] = 'Accept: text/plain';
						break;
			default :
						throw new Exception("Unknown option $type\n");
		}
		$options = [CURLOPT_PUT => true];

		// check if file exists and is readable
		if($file && file_exists($file) && is_readable($file))
		{
			$options[CURLOPT_INFILE] = fopen($file,'r');
			$options[CURLOPT_INFILESIZE] = filesize($file);
		}
		else
		{
			// Throw exception in case of failure at opening the file
			throw new Exception("Uable to open file : $file\n");
		}

		// Set the curl headers
		$options[CURLOPT_HTTPHEADER] = $headers;

		//init the curl and options
		$options[CURLOPT_URL] = "http://{$this->host}:{$this->port}"."/$resource";

		// Get the response
		
		list($response , $status) = $this->run($options);

		switch($status)
		{
			case 200:
					if($type == 'meta')
					{
						$response = Metadata::make($response, $file);
					}
					break;
			case 204:
					$response = null;
					break;
			case 415:
					throw new Exception("Media type not supported by apache tika\n");
					break;
			case 422:
					throw new Exception("Apache tika do not support this document type\n"); 
					break;
			case 500:
					throw new Exception("Something wrong with server\n");
					break;
			default :
					throw new Exception("Unexpected response\n");
					break;
		}

		return $response;
	}
}

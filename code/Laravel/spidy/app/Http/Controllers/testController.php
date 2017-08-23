<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use File;
use App\Custom\ApacheTika\ApacheTika as ApacheTika;
use Elasticsearch\ClientBuilder;

class testController extends Controller
{
    public function crawler()
    {
        $tikaServer = ApacheTika::make();
        $tikaServer->show();
        $text = $tikaServer->getText('/home/development/pdf_root/annual_report_2009.pdf');
        echo '<hr>Text<hr><br/>'.$text.'<br/><hr>';
    }

    public function search()
    {
    	$client = ClientBuilder::create()->build();

    	$param = [
    		'index'	=>	'document',
    		'type'	=>	'pdf',
    		'body'	=>	[
    			'query'	=>	[
    				'match'	=>	[
    					'file_body'	=>	'have a problem of Split brain situations'
    				]
    			]
    		]
    	];

    	$response = $client->search($param);
    	print_r($response);
    }
}

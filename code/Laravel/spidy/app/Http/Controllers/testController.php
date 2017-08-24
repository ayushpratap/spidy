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
        //$text = $tikaServer->getText('/home/development/pdf_root/annual_report_2009.pdf');
        //echo '<hr>Text<hr><br/>'.$text.'<br/><hr>';
        $metaData = $tikaServer->getMetaData('/home/development/pdf_root/tika_tutorial.pdf');
        print_r($metaData);
    }

    public function search()
    {
    	$client = ClientBuilder::create()->build();

    	$param = [
    		'index'	=>	'document',
    		'type'	=>	'pdf',
    		'body'	=>	[
    			'query'	=>	[
    				'match_phrase'	=>	[
    					'file_body'	=>	'just some random words'
    				]
    			]
    		]
    	];

    	$response = $client->search($param);
    	print_r($response);
    }
}

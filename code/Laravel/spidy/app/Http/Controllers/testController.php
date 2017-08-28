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

    public function search(Request $request)
    {
    	$q = $request->q;
    	$searchValue = $q;
    	//die($q);
    	$q = "'".$q."'";
    	$client = ClientBuilder::create()->build();

    	$param = [
    		'scroll'=>	'30s',
    		'size'	=>	1,
    		'index'	=>	'document',
    		'type'	=>	'pdf',
    		'body'	=>	[
    			'query'	=>	[
    				'match_phrase'	=>	[
    					'file_body'	=>	$q
    				]
    			]
    		]
    	];
    	$response = $client->search($param);
    	$scroll_id = $response['_scroll_id'];
    	//dd($response);
    	while(TRUE)
    	{
    		$test = $client->scroll([
    				'scroll_id'	=>	$scroll_id,
    				'scroll'	=>	'30s',
    			]);
    		if(count($test['hits']['hits']) > 0)
    		{
    			$scroll_id = $test['_scroll_id'];
    			array_push($response,$test);
    		}
    		else
    		{
    			break;
    		}
    	}
    	if($response['hits']['total'] > 0)
    	{
    		$send = $response;
    		$hits = $response['hits']['total'];
    	}
    	else
    	{
    		$send = "Sorry : no such file.";
    		$hits = 0;
    	}
    	return view('search_page',['responses' => $send,
    								'hits'	   => $hits,
    								'searchValue'=>	$searchValue
    		]);
    }
}

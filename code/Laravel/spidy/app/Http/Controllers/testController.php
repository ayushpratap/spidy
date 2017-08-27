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
    	$q = "'".$q."'";
    	$client = ClientBuilder::create()->build();

    	$param = [
    		'scroll'=>	'5s',
    		'size'	=>	5,
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
    	echo "<pre>",print_r($response),"</pre>";
    	echo "<hr><pre>";
    	$i = 1;
    	while (isset($response['hits']['hits']) && count($response['hits']['hits'])) {
    		print_r($response['hits']['hits']);
    		$scroll_id = $response['_scroll_id'];
    		$response = $client->scroll([
    				'scroll_id'	=>	$scroll_id,
    				'scroll'	=>	'5s'
    			]);
    	}
    	echo "</pre>";

die();
//    	$response = $client->search($param);
// 	  	echo "<pre>",print_r($response),"</pre>";
//    	die('i am dead');
    	if($response['hits']['total'] > 0)
    	{
    		$send = $response['hits']['hits'];
    		$hits = $response['hits']['total'];
    	}
    	else
    	{
    		$send = "Sorry : no such file.";
    		$hits = 0;
    	}
    	//print_r($send);
    	//die();
    	return view('search_page',['responses' => $send,
    								'hits'	   => $hits
    		]);
    	//echo '<pre>',print_r($response),'</pre>';
    	//return $response;
    }

    public function search_demo(Request $request)
    {
    	echo "This is testController@search_demo<br/><hr>";
    	echo $request->q;
    	# code...
    }
}

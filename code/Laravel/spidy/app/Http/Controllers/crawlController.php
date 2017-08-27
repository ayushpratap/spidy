<?php

namespace App\Http\Controllers;
//include 'vendor/autoload.php';
use Illuminate\Http\Request;
use File;
use Illuminate\Support\Collection;
use App\Custom\ApacheTika\ApacheTika as ApacheTika;
use Elasticsearch\ClientBuilder;

class crawlController extends Controller
{   
    public function crawler()
    {
        $dirPath = '/home/development/pdf_root';
        $this->getFileFolderTree($dirPath);
        return redirect()->route('home');
    }

    public function getFileFolderTree($rootDirectory)
    {
        if(0 == (File::directories($rootDirectory)))
        {
          $this->searchFiles($rootDirectory);
        }
        else
        {
          $this->goIntoFolder($rootDirectory);
        }
    }

    public function goIntoFolder($dirPath)
    {
      // Get all the direct sub folders of the root folder
      try
      {
        $dirList = File::directories($dirPath);
      }
      catch(\App\Exceptions\InvalidArgumentException $e)
      {
        echo $e->getMessage(); 
      }
     
      if(count($dirList) == 0)
      {
        //search for files now
        $this->searchFiles($dirPath);
      }
      else
      {
        // Loop through the list of diectories
        foreach ($dirList as $dir) 
        {
          // Recursivly search selected directory
          $this->goIntoFolder($dir); 
        }
      }
      $this->searchFiles($dirPath);
    }

    public function searchFiles($dirPath)
    {
      // Read all files
      $files = File::files($dirPath);
      // If no files exists
      if(count($files) > 0)
      {
        foreach ($files as $file) 
        {
          // Check if file is a pdf file.
           if(0 == strcasecmp('pdf',File::extension($file)))
           {
              // Read the file
              //echo "2. Reading files from :",$dirPath;
              $this->readFileData($file);
           }
        }   
      }
    }

    public function readFileData($file)
    {
      //echo "Filname : ",$file,"<br/>";
      // Create elasticsearch handle
            $elasticsearchHandle = ClientBuilder::create()->build();

      // Create Apache Tika handle
            $tikaHandle = ApacheTika::make();
      
      // Get the content of file : @var $file
            $content = $tikaHandle->getText($file);

      // Get the base name of the file
              $file_name = basename($file);
      // Escape epecial characters
      
      // Create JSON equivalent associative array
      
        $param = [
        'index' =>  'document',
        'type'  =>  'pdf',
        'body'  =>  [
                      'file_name' =>  $file_name,
                      'file_body' =>  $content
                    ]
      ];

      // Send it to elasticsearch
        $response = $elasticsearchHandle->index($param);
      print_r($response);
      //echo "<br/><hr>";
     // echo "File name : ",$file_name,"<br/>";
      //echo "<br/>";
      //echo "Body </br/> <hr>",$content;
    }
  }
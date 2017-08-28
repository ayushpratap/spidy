<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use File;
use Illuminate\Support\Collection;
use App\Custom\ApacheTika\ApacheTika as ApacheTika;
use Elasticsearch\ClientBuilder;

class crawlController extends Controller
{   
    public function crawler()
    {
        $dirPath = '/home/development/pdf_test';
        $this->getFileFolderTree($dirPath);

        // Redirects to home page
        return redirect()->route('home');
    }

    public function getFileFolderTree($rootDirectory)
    {
        //Check if root directory contains other directories
        if(0 == (File::directories($rootDirectory)))
        {
          // No other directories are found then read files
          $this->searchFiles($rootDirectory);
        }
        else
        {
          // If other directories are found then go into sub-directories
          $this->goIntoFolder($rootDirectory);
        }
    }

    public function goIntoFolder($dirPath)
    {
      // Get all the direct sub folders of the root folder
      try
      {
        //  Get all the files
        $dirList = File::directories($dirPath);
      }
      catch(\App\Exceptions\InvalidArgumentException $e)
      {
        echo $e->getMessage(); 
      }
     
      if(count($dirList) == 0)
      {
        //  Search for files now
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

      //  Search for files
      $this->searchFiles($dirPath);
    }

    public function searchFiles($dirPath)
    {
      // Array of files in directory : @var $dirPath
      $files = File::files($dirPath);

      // If files exists
      if(count($files) > 0)
      {

        //  Loop through array and read files
        foreach ($files as $file) 
        {

          // Check if file is a pdf file.
           if(0 == strcasecmp('pdf',File::extension($file)))
           {
            
              // Read the file
              $this->readFileData($file);
           }
        }   
      }
    }

    public function readFileData($file)
    {
      // Create elasticsearch handle
            $elasticsearchHandle = ClientBuilder::create()->build();

      // Create Apache Tika handle
            $tikaHandle = ApacheTika::make();
      
      // Get the content of file : @var $file
            $content = $tikaHandle->getText($file);

      //  Get meta data of file : @var $file            
            $meta = $tikaHandle->getMetaData($file);

      // Get the base name of the file
              $file_name = basename($file);
      
      // Create JSON equivalent associative array
        $param = [
        'index' =>  'document',
        'type'  =>  'pdf',
        'body'  =>  [
                      'file_name' =>  $file_name,
                      'file_body' =>  $content
                    ]
      ];

      // Index the document
        $response = $elasticsearchHandle->index($param);
    }
  }
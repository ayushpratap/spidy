<?php

namespace App\Http\Controllers;
//include 'vendor/autoload.php';
use Illuminate\Http\Request;
use File;
use Illuminate\Support\Collection;


class crawlController extends Controller
{
    // TO-DO : Check for Laravel addon for documentation or some other sublime tool
    
  	public function crawler()
  	{
        set_time_limit(0);
        $start = microtime();
        $dirPath = '/home/development/pdf_root';
        $this->getFileFolderTree($dirPath);
        $end = microtime();
        echo "Total time taken :".($end-$start)."sec<br/>";
  	}

    public function getFileFolderTree($rootDirectory)
    {
      echo "getFileFolderTree<br/>";
        if(0 == (File::directories($rootDirectory)))
        {
          $this->searchFiles($rootDirectory);
          return;
        }
        else
        {
          $this->goIntoFolder($rootDirectory);
          return;
        }
    }

    public function goIntoFolder($dirPath)
    {
      echo "goIntoFolder<br/>";
      // Get all the direct sub folders of the root folder
      try
      {
        $dirList = File::directories($dirPath);
      }
      catch(\App\Exceptions\InvalidArgumentException $e)
      {
        echo $e->getMessage(); 
        return false;       
      }
     
      if(count($dirList) == 0)
      {
        //search for files now
        if($this->searchFiles($dirPath))
          return true;
        else
          return false;
      }
      else
      {
        // Loop through the list of diectories
        foreach ($dirList as $dir) 
        {
          // Print name of the selected directory
          echo "Folder name : ",basename($dir)," Parent Folder :",basename($dirPath),"<br/>";

          // Recursivly search selected directory
          $this->goIntoFolder($dir); 
        }
      }
    }

    public function searchFiles($dirPath)
    {
      echo "searchFiles<br/>";
      // Read all files
      $files = File::files($dirPath);
      $result = false;

      // If no files exists
      if(count($files) > 0)
      {
        foreach ($files as $file) 
        {
          // Check if file is a pdf file.
           if(0 == strcasecmp('pdf',File::extension($file)))
           {
              // Read the file
              //echo "2. Reading files from : ",$dirPath;
              if($this->readFileData($file))
                $result = true;
              else
                $result = false;
           }
           echo "<hr>";
        }   
        if($result)     
          $result = ture;
        else
          $result = false;
      }
      return $result;
    }

    public function readFileData($file)
    {
      echo "readFileData<br/>";
      //echo "Reading file : ",$file,"<br/>";
        // Read the file using PdfToText
        
        // Build PdfTotext object
        $parser = new \Smalot\PdfParser\Parser();
        $pdfLoad = $parser->parseFile($file);
        $content = $pdfLoad->getText();
        $txtFilename = basename($file).".txt";
        $bytesWritten = File::append($txtFilename,$content);
        if($bytesWritten)
        {
          echo "success : ",$file;
          return true;
        }
        else
        {
          echo "Faliure : ",$file;
          return false;
        }
        unset($parser);

        // Retrieve all details    
    }
}

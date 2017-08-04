<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use File;
use Illuminate\Support\Collection;

class crawlController extends Controller
{
    //
    
  	public function crawler()
  	{
        $dirPath = '/home/development/Pictures';
        $this->getFileFolderTree($dirPath);
  	}

    public function getFileFolderTree($rootDirectory)
    {
        $this->goIntoFolder($rootDirectory);
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
        require $e->getMessage();        
      }
      if(count($dirList) === 0)
      {
        //search for files now
        $this->searchFiles($dirPath);
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
        echo "<hr><br/>";
      }

      // Get all the files of root directory
       $this->searchFiles($dirPath);
    }

    public function searchFiles($dirPath)
    {
      // Read all files
      $files = File::files($dirPath);
      $result = FALSE;

      // If no files exists
      if(count($files) > 0)
      {
        foreach ($files as $file) 
        {
            echo "File name : ",basename($file)," **<b>Parent Folder</b>**:",basename($dirPath),"<br/>";

            // Write to XML file
        }        
        $result = TRUE;
      }
      return $result;
    }
}

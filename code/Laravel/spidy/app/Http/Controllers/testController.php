<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use File;
class testController extends Controller
{
    public function crawler()
    {
    	$dirPath = '/home/rex/1';
    	try
      {
        $dirList = File::directories($dirPath);
      }
      catch(\App\Exceptions\InvalidArgumentException $e)
      {
        require $e->getMessage();        
      }
        // Loop through the list of diectories
        foreach ($dirList as $dir) 
        {
          // Print name of the selected directory
         // echo "-Folder name : ",basename($dir)," Parent Folder :",basename($dirPath),"<br/>";
        	echo basename($dir),"<br/>";

          // Recursivly search selected directory
       //   $this->goIntoFolder($dir); 
        }
        echo "<hr><br/>";
      }

      public function phpinfo()
      {
        phpinfo();
      }
}

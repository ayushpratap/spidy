<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class crawlController extends Controller
{
    //
    
  	public function crawler()
  	{
  		# Open file_system.xml
  		# Create if don't exists
  		# Check crawl_count , 
  		# 	= 0 - crawl and read all files
  		# 		Start writing file-folder tree
  		# 	> 0	- Check for updates only. 
  		# 		Update file-folder tree
  		
  		$test = new RecursiveDirectoryIterator();
      for (new RecursiveIteartorIterator($test) as $filename => $files) { 
        # cod...
        # 
        echo $filename . ' - ' . $files->getSize() . ' bytes <br/>';
      }
  	}
}

<?php 
$ekstenzije = array('jpg', 'jpeg'); //Delete JPG files only 
			$files = array(); 
			$putanja = "screenshots/"; 
			$dir = opendir($putanja); 
			$count=0; 
			$debug = ""; 
			while( ($file = readdir($dir)) != false ) 
				{ 
					if( !is_dir($file) && !in_array($file,array('.','..')) && in_array(substr($file,strrpos($file,'.')+1),$ekstenzije) ) 
						{ 
							if (file_exists($putanja.$file)) 
				 				{$count++; $debug.= "\n$count | $file"; unlink($putanja.$file); } 
					   } 
				} 
closedir($dir);?>
<?php

if (isset($_SERVER["HTTP_REFERER"])) {
        header("Location: " . $_SERVER["HTTP_REFERER"]);
    }
?>
<?php
    session_start();
    $_SESSION['lines'] = 0;
    $filename = "/var/log/apache2/error.log";
    //chmod($filename , 777);
    $file = fopen($filename, "w");	
	fclose($file);

?>
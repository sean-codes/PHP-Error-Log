<?php
    session_start();
    $_SESSION['lines'] = 0;

    $file = fopen("..\..\logs\php_error.log", "w");	
	fclose($file);

?>
<?php
    session_start();
    if(isset($_SESSION['lines']) == false || isset($_GET['reset']) == true){
        $_SESSION['lines'] = 0;
    }

    //Save current lines for checking change
    $old_lines = $_SESSION['lines'];
    
    //Only poll for set time
    $poll_time = time();

    do {
	    clearstatcache();
        //Check lines
        $filename = "/var/log/apache2/error.log";
        //chmod($filename , 777);
        $log = file($filename);
        if(count($log) > 0){
            $_SESSION['lines'] = count($log);  
        }
	    usleep(500); 
    }
    while($_SESSION['lines'] == $old_lines && abs($poll_time - time()) < 2);

    //File was Modified || Reseting Poll
    $errors = array();
    
    
    $cnt = 0;
    for($i = $old_lines; $i < $_SESSION['lines']; $i++){
        $errors[$cnt] = array();
        //[Date] [type] [pid] [client] Test Error Log, referer: location
        //We will grab the Brackets First
        $break = explode(']', $log[$i]);
        $errors[$cnt]['date'] = trim(str_replace('[', '', $break[0]));
        $errors[$cnt]['type'] = trim(str_replace('[', '', $break[1]));
        $errors[$cnt]['pid'] = trim(str_replace('[', '', $break[2]));
        $errors[$cnt]['client'] = trim(str_replace('[client', '', $break[3]));
        
        //Now the error and location
        $break = explode(',', $break[4]);
        $errors[$cnt]['error'] = trim($break[0]);
        $errors[$cnt]['location'] = trim(str_replace('referer:', '', $break[1]));

        $cnt += 1;
    }
    
    echo json_encode($errors);
?>
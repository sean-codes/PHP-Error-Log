<?php
session_start();
$_SESSION['filename'] = "/var/log/apache2/error.log";

$function = $_GET['function'];
switch($function){
    case 'clear':
        clear();
        break;
    
    case 'test':
        test();
        break;
        
    case 'check':
        check();
        break;
}

function clear(){
    $_SESSION['lines'] = 0;
    
    $file = fopen($_SESSION['filename'], "w");	
	fclose($file);
}

function test(){
    error_log('Test Error Log');
}

function check(){
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
        $log = file($_SESSION['filename']);
        if(count($log) > 0){
            $_SESSION['lines'] = count($log);  
        }
        session_write_close();
	    usleep(10000); 
        session_start();
    }
    while($_SESSION['lines'] == $old_lines && abs($poll_time - time()) < 2);

    //File was Modified || Reseting Poll
    $errors = array();
    for($i = $old_lines; $i < $_SESSION['lines']; $i++){
        $cnt = count($errors); $errors[$cnt] = array();
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
    }
    
    echo json_encode($errors);
}
?>

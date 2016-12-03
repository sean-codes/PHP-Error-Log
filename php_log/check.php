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
        $log = file("..\..\logs\php_error.log");
        if(count($log) > 0){
            $_SESSION['lines'] = count($log);  
        }
	    usleep(500); 
    }
    while($_SESSION['lines'] == $old_lines && abs($poll_time - time()) < 1);

    //File was Modified || Reseting Poll
    $errors[0] = "";
    $tmp_cnt = 0;
    for($i = $old_lines; $i < $_SESSION['lines']; $i++){
        $errors[$tmp_cnt] = $log[$i];
        $tmp_cnt += 1;
    }

    $data['lines'] = $_SESSION['lines'];
    $data['errors'] = json_encode($errors);
    
    
    echo json_encode($data);

?>
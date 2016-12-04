<?php

    csLogArray($arr){
        $log = 'Length: ' . strval(count($arr));
        $log .= '<ul>';
        foreach($arr as $ele){
            $log .= "<li>{$ele}</li>";
        }
        $log .= '</ul>';
        error_log($log);

    }
?>

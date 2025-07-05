<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: text/event-stream");
header("Cache-Control: no-cache");


$pctg = 0;

while(true){



    echo '{"data": {"key":'.$pctg.'}}';

    //ob_end_flush();
    ob_flush();
    flush();

    if(connection_aborted()){
        break;
        exit();
    }


    $pctg++;

    sleep(2);
}
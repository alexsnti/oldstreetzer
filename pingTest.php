<?php
pingDomain("37.187.20.195");
function pingDomain($domain){
    $starttime = microtime(true);
    $file      = fsockopen ($domain, 21025, $errno, $errstr, 10);
    $stoptime  = microtime(true);
    $status    = 0;

    if (!$file) $status = -1;  // Site is down
    else {
        fclose($file);
        $status = ($stoptime - $starttime) * 1000;
        $status = floor($status);
    }
    echo $status;
}

?>
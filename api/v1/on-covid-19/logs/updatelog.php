<?php
function updateLog($logEntry){
    $logFile = $_SERVER['DOCUMENT_ROOT']."/api/v1/on-covid-19/logs/log.txt";
    $fh = fopen($logFile, 'a') or die("can't open file");
    fwrite($fh, $logEntry);
    fclose($fh);
}
?>
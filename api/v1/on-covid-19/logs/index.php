<?php
include "updatelog.php";
header("content-Type:text/plain");

$executionTime = (int)(microtime(true) - $_SERVER["REQUEST_TIME_FLOAT"]) * 1000;
$request = 'GET'.'   '.$_SERVER['REQUEST_URI'].'   '.http_response_code().'   '.$executionTime.'ms'."\n";
updateLog($request);
readfile($_SERVER['DOCUMENT_ROOT']."/api/v1/on-covid-19/logs/log.txt");

?>
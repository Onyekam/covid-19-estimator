<?php
header("Content-Type: application/json; charset=UTF-8");
include "../../../../src/estimator.php";
include "../logs/updatelog.php";

$data = array(
    "region" => array(
      "name" => "Africa",
      "avgAge" => 19.7,
      "avgDailyIncomeInUSD" => 5,
      "avgDailyIncomePopulation" => 0.71
    ),
    "periodType" => "days",
    "timeToElapse" => 58,
    "reportedCases" => 674,
    "population" => 66622705,
    "totalHospitalBeds" => 1380614
);

echo json_encode(covid19ImpactEstimator($data));
$executionTime = (int)(microtime(true) - $_SERVER["REQUEST_TIME_FLOAT"]) * 1000;
$request = 'GET'.'   '.$_SERVER['REQUEST_URI'].'   '.http_response_code().'   '.$executionTime.'ms'."\n";
updateLog($request);
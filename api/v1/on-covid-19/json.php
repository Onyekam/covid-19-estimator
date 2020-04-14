<?php
include "../../../src/estimator.php";

$data = array(
    "region" => array(
      "name" => "Africa",
      "avgAge" => 19.7,
      "avgDailyIncomeInUSD" => 5,
      "avgDailyIncomePopulation" => 0.71
    ),
    "periodType" => "months",
    "timeToElapse" => 58,
    "reportedCases" => 674,
    "population" => 66622705,
    "totalHospitalBeds" => 1380614
  );

  covid19ImpactEstimator($data);
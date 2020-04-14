<?php

//echo (int)(50 / 3);

$data = json_encode(array(
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
));

function covid19ImpactEstimator($data) {
  
  $impact = json_encode(
    array(
      'currentlyInfected' => estimateImpact($data->reportedCases), 
      'infectionsByRequestedTime' => estimateInfectionsByRequestedTime($impact->currentlyInfected),
      'severeCasesByRequestedTime' => estimateSevereCasesByRequestedTime($impact->infectionsByRequestedTime),
      'hospitalBedsByRequestedTime' => estimateHospitalBedsByRequestedTime($impact->severeCasesByRequestedTime, $data->totalHospitalBeds),
      'casesForICUByRequestedTime' => estimateCasesForICUByRequestedTime($impact->infectionsByRequestedTime),
      'casesForVentilatorsByRequestedTime' => estimateCasesForVentilatorsByRequestedTime($impact->infectionsByRequestedTime),
      'dollarsInFlight' => estimateDollarsInFlight($impact->infectionsByRequestedTime, $data->region->avgDailyIncomePopulation, $data->region->avgDailyIncomeInUSD)
    ));

  $severeImpact = json_encode(
    array(
      'currentlyInfected' => estimateSevereImpact($data->reportedCases),
      'infectionsByRequestedTime' => estimateInfectionsByRequestedTime($severeImpact->currentlyInfected),
      'severeCasesByRequestedTime' => estimateSevereCasesByRequestedTime($severeImpact->infectionsByRequestedTime),
      'hospitalBedsByRequestedTime' => estimateHospitalBedsByRequestedTime($severeImpact->severeCasesByRequestedTime, $data->totalHospitalBeds),
      'casesForICUByRequestedTime' => estimateCasesForICUByRequestedTime($severeImpact->infectionsByRequestedTime),
      'casesForVentilatorsByRequestedTime' => estimateCasesForVentilatorsByRequestedTime($severeImpact->infectionsByRequestedTime),
      'dollarsInFlight' => estimateDollarsInFlight($severeImpact->infectionsByRequestedTime, $data->region->avgDailyIncomePopulation, $data->region->avgDailyIncomeInUSD)
    ));

  $estimatorOuput = json_encode(
    array(
      'data' => $data,
      'impact' => $impact,
      'severeImpact' => $severeImpact
    ));
  return $estimatorOutput;
}

function estimateImpact($reportedCases) {
  $currentlyInfected = (int)($reportedCases * 10);
  return $currentlyInfected;
}

function estimateSevereImpact($reportedCases) {
  $currentlyInfected = (int)($reportedCases * 50);
  return $currentlyInfected;
}

function estimateInfectionsByRequestedTime($currentlyInfected, $timeToElapse, $periodType = "days") {
  switch ($periodType) {
    case "weeks":
      $days = $timeToElapse * 7;
    case "months":
      $days = $timeToElapse * 30;
    default:
      $days = $timeToElapse;
  }
  $setsOf3Days = (int)($days / 3);
  $infectionsByRequestedTime = (int)($currentlyInfected * pow(2, $setsOf3Days));
  return $infectionsByRequestedTime;
  
}

function estimateSevereCasesByRequestedTime($infectionsByRequestedTime) {
  $severeCasesByRequestedTime = (int)($infectionsByRequestedTime * 0.15);
  return $severeCasesByRequestedTime;
}

function estimateHospitalBedsByRequestedTime($severeCasesByRequestedTime, $totalHospitalBeds) {
  $availableBeds = (int)($totalHospitalBeds * 0.35);
  $hospitalBedsByRequestedTime = $availableBeds - $severeCasesByRequestedTime;
  return $hospitalBedsByRequestedTime;
}

function estimateCasesForICUByRequestedTime($infectionsByRequestedTime) {
  $casesForICUByRequestedTime = (int)($infectionsByRequestedTime * 0.05);
  return $casesForICUByRequestedTime;
}

function estimateCasesForVentilatorsByRequestedTime($infectionsByRequestedTime) {
  $casesForVentilatorsByRequestedTime = (int)($infectionsByRequestedTime * 0.02);
  return $casesForVentilatorsByRequestedTime;
}

function estimateDollarsInFlight($infectionsByRequestedTime, $avgDailyIncomePopulation, $avgDailyIncomeInUSD) {
  $dollarsInFlight = (int)(($infectionsByRequestedTime * $avgDailyIncomePopulation * $avgDailyIncomeInUSD)/30); 
  return $dollarsInFlight;
}

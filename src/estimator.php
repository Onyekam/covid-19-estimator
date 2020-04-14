<?php

function covid19ImpactEstimator($data) {
  $impact['currentlyInfected'] = estimateImpact($data['reportedCases']);
  $impact['infectionsByRequestedTime'] = estimateInfectionsByRequestedTime($impact['currentlyInfected'], $data['timeToElapse'], $data['periodType']);
  $impact['severeCasesByRequestedTime'] = estimateSevereCasesByRequestedTime($impact['infectionsByRequestedTime']);
  $impact['hospitalBedsByRequestedTime'] = estimateHospitalBedsByRequestedTime($impact['severeCasesByRequestedTime'], $data['totalHospitalBeds']);
  $impact['casesForICUByRequestedTime'] = estimateCasesForICUByRequestedTime($impact['infectionsByRequestedTime']);
  $impact['casesForVentilatorsByRequestedTime'] = estimateCasesForVentilatorsByRequestedTime($impact['infectionsByRequestedTime']);
  $impact['dollarsInFlight'] = estimateDollarsInFlight($impact['infectionsByRequestedTime'], $data['region']['avgDailyIncomePopulation'], $data['region']['avgDailyIncomeInUSD']);

  $severeImpact['currentlyInfected'] = estimateSevereImpact($data['reportedCases']);
  $severeImpact['infectionsByRequestedTime'] = estimateInfectionsByRequestedTime($severeImpact['currentlyInfected'], $data['timeToElapse'], $data['periodType']);
  $severeImpact['severeCasesByRequestedTime'] = estimateSevereCasesByRequestedTime($severeImpact['infectionsByRequestedTime']);
  $severeImpact['hospitalBedsByRequestedTime'] = estimateHospitalBedsByRequestedTime($severeImpact['severeCasesByRequestedTime'], $data['totalHospitalBeds']);
  $severeImpact['casesForICUByRequestedTime'] = estimateCasesForICUByRequestedTime($severeImpact['infectionsByRequestedTime']);
  $severeImpact['casesForVentilatorsByRequestedTime'] = estimateCasesForVentilatorsByRequestedTime($severeImpact['infectionsByRequestedTime']);
  $severeImpact['dollarsInFlight'] = estimateDollarsInFlight($severeImpact['infectionsByRequestedTime'], $data['region']['avgDailyIncomePopulation'], $data['region']['avgDailyIncomeInUSD']);

  $estimatorOutput = array(
      'data' => $data,
      'impact' => $impact,
      'severeImpact' => $severeImpact
    );
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

function estimateInfectionsByRequestedTime($currentlyInfected, $timeToElapse, $periodType) {
  switch ($periodType) {
    case "weeks":
      $days = $timeToElapse * 7;
    break;
    case "months":
      $days = $timeToElapse * 30;
    break;
    default:
      $days = $timeToElapse;
  }
  
  $setsOf3Days = (int)($days / 3);
  //echo $setsOf3Days."<br/>";
  $infectionsByRequestedTime = ($currentlyInfected * pow(2, $setsOf3Days));
  //echo $infectionsByRequestedTime; die;
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

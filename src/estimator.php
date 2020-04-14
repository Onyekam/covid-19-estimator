<?php

function covid19ImpactEstimator($data) {
  $impact['currentlyInfected'] = estimateImpact($data['reportedCases']);
  $impact['infectionsByRequestedTime'] = estimateInfectionsByRequestedTime($impact['currentlyInfected'], $data['timeToElapse'], $data['periodType']);
  $impact['severeCasesByRequestedTime'] = estimateSevereCasesByRequestedTime($impact['infectionsByRequestedTime']);
  $impact['hospitalBedsByRequestedTime'] = estimateHospitalBedsByRequestedTime($impact['severeCasesByRequestedTime'], $data['totalHospitalBeds']);
  $impact['casesForICUByRequestedTime'] = estimateCasesForICUByRequestedTime($impact['infectionsByRequestedTime']);
  $impact['casesForVentilatorsByRequestedTime'] = estimateCasesForVentilatorsByRequestedTime($impact['infectionsByRequestedTime']);
  $impact['dollarsInFlight'] = estimateDollarsInFlight($impact['infectionsByRequestedTime'], $data['region']['avgDailyIncomePopulation'], $data['region']['avgDailyIncomeInUSD'], $data['timeToElapse'], $data['periodType']);

  $severeImpact['currentlyInfected'] = estimateSevereImpact($data['reportedCases']);
  $severeImpact['infectionsByRequestedTime'] = estimateInfectionsByRequestedTime($severeImpact['currentlyInfected'], $data['timeToElapse'], $data['periodType']);
  $severeImpact['severeCasesByRequestedTime'] = estimateSevereCasesByRequestedTime($severeImpact['infectionsByRequestedTime']);
  $severeImpact['hospitalBedsByRequestedTime'] = estimateHospitalBedsByRequestedTime($severeImpact['severeCasesByRequestedTime'], $data['totalHospitalBeds']);
  $severeImpact['casesForICUByRequestedTime'] = estimateCasesForICUByRequestedTime($severeImpact['infectionsByRequestedTime']);
  $severeImpact['casesForVentilatorsByRequestedTime'] = estimateCasesForVentilatorsByRequestedTime($severeImpact['infectionsByRequestedTime']);
  $severeImpact['dollarsInFlight'] = estimateDollarsInFlight($severeImpact['infectionsByRequestedTime'], $data['region']['avgDailyIncomePopulation'], $data['region']['avgDailyIncomeInUSD'], $data['timeToElapse'], $data['periodType']);

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

function obtainNumberOfDays($timeToElapse, $periodType) {
  switch ($periodType) {
    case "weeks":
      $days = $timeToElapse * 7;
    break;
    case "months":
      $days = $timeToElapse * 30;
    break;
    default:
      $days = $timeToElapse;
    break;
  }
  return $days;
}

function estimateInfectionsByRequestedTime($currentlyInfected, $timeToElapse, $periodType) {
  $days = obtainNumberOfDays($timeToElapse, $periodType);
  $setsOf3Days = (int)($days / 3);
  $infectionsByRequestedTime = (int)($currentlyInfected * pow(2, $setsOf3Days));
  return $infectionsByRequestedTime;
}

function estimateSevereCasesByRequestedTime($infectionsByRequestedTime) {
  $severeCasesByRequestedTime = (int)($infectionsByRequestedTime * 0.15);
  return $severeCasesByRequestedTime;
}

function estimateHospitalBedsByRequestedTime($severeCasesByRequestedTime, $totalHospitalBeds) {
  $availableBeds = $totalHospitalBeds * 0.35;
  $hospitalBedsByRequestedTime = (int)($availableBeds - $severeCasesByRequestedTime);
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

function estimateDollarsInFlight($infectionsByRequestedTime, $avgDailyIncomePopulation, $avgDailyIncomeInUSD, $timeToElapse, $periodType) {
  $days = obtainNumberOfDays($timeToElapse, $periodType);
  $dollarsInFlight = (int)(($infectionsByRequestedTime * $avgDailyIncomePopulation * $avgDailyIncomeInUSD) / $days); 
  return $dollarsInFlight;
}

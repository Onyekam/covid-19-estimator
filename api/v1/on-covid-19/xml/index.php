<?php
header("content-Type:text/xml");
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

$estimatorOutput = covid19ImpactEstimator($data);

$xml = new SimpleXMLElement("<?xml version=\"1.0\"?><covid_19_estimator></covid_19_estimator>");
array_to_xml($estimatorOutput, $xml);
print $xml->asXML();
$executionTime = (int)(microtime(true) - $_SERVER["REQUEST_TIME_FLOAT"]) * 1000;
$request = 'GET'.'   '.$_SERVER['REQUEST_URI'].'   '.http_response_code().'   '.'0'.$executionTime.'ms'."\n";
updateLog($request);


function array_to_xml($array, &$xml_user_info) {
    foreach($array as $key => $value) {
        if(is_array($value)) {
            if(!is_numeric($key)){
                $subnode = $xml_user_info->addChild("$key");
                array_to_xml($value, $subnode);
            }else{
                $subnode = $xml_user_info->addChild("item$key");
                array_to_xml($value, $subnode);
            }
        }else {
            $xml_user_info->addChild("$key",htmlspecialchars("$value"));
        }
    }
}
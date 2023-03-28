<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../model/config.php';
include_once '../model/database.php';
include_once '../controller/profile.php';

$database = new Database();
$db = $database->getConnection();
$profile = new Profile($db);

$data = array(
    'cardData' => $profile->cardData(),
    'ageData' => $profile->chartData(''),
    'vaccinatedData' => $profile->chartData('vaccinated'),
    'diagnoseData' => $profile->chartData('covidDiagnosed'),
    'encounterData' => $profile->chartData('covidEncounter')
);
http_response_code(201);
echo json_encode($data);
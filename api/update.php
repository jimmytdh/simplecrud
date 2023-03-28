<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../model/config.php';
include_once '../model/database.php';
include_once '../controller/profile.php';

$database = new Database();
$db = $database->getConnection();
$profile = new Profile($db);

$profile->id = $_POST['id'];
$profile->firstname = $_POST['firstname'];
$profile->lastname = $_POST['lastname'];
$profile->gender = $_POST['gender'];
$profile->dob = $_POST['dob'];
$profile->contact = $_POST['contact'];
$profile->bodyTemp = $_POST['bodyTemp'];
$profile->covidDiagnosed = $_POST['covidDiagnosed'];
$profile->covidEncounter = $_POST['covidEncounter'];
$profile->vaccinated = $_POST['vaccinated'];
$profile->nationality = $_POST['nationality'];

if($profile->update()){
    http_response_code(201);
    echo json_encode("Record updated successfully.");
} else{
    http_response_code(304);
    echo json_encode("Record could not be updated.");
}


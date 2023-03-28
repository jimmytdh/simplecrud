<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");

include_once '../model/config.php';
include_once '../model/database.php';
include_once '../controller/profile.php';

$database = new Database();
$db = $database->getConnection();
$profile = new Profile($db);

$profile->id = $_POST['id'];

if($profile->delete()){
    http_response_code(201);
    echo json_encode("Record deleted successfully.");
} else{
    http_response_code(304);
    echo json_encode("Record could not be deleted.");
}

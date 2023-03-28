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
$items = new Profile($db);

$param = isset($_GET['id']) ? $_GET['id'] : null;

$result = null;
if (isset($_GET['id'])){
    $result = $items->getByID($param);
    http_response_code(201);
    echo json_encode($result);
}else {
    $result = $items->getAll();
    http_response_code(201);
    return $result;
}
?>
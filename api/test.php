<?php

include_once '../model/config.php';
include_once '../model/database.php';
include_once '../controller/profile.php';

$database = new Database();
$db = $database->getConnection();
$items = new Profile($db);

$result = $items->getAll();
print_r($result);
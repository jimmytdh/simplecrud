<?php

include('../model/conn.php');
if(isset($_GET['id']) && !empty($_GET['id'])) {
    // get the id value and sanitize it
    $id = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);

    // SQL delete statement
    $sql = "DELETE FROM records WHERE id=$id";

    // execute the delete statement
    if (mysqli_query($conn, $sql)) {
        echo json_encode(array('status' => 'success'));
    } else {
        echo json_encode(array('status' => "Error deleting record: " . mysqli_error($conn)));
    }

    // close database connection
    mysqli_close($conn);
}else {
    // if id parameter is missing or empty, return 400 response code
    http_response_code(400);
}
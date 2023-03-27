<?php

include('../model/conn.php');
if(isset($_GET['id']) && !empty($_GET['id'])) {
    // get the id value and sanitize it
    $id = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);

    // prepare sql statement
    $stmt = $conn->prepare("SELECT * FROM records WHERE id=?");
    $stmt->bind_param("i", $id);
    // execute statement
    $stmt->execute();

    // get result set
    $result = $stmt->get_result();

    // check if there is a matching record
    if ($result->num_rows > 0) {
        // fetch the record as an associative array
        $record = $result->fetch_assoc();

        // output the record as JSON
        echo json_encode($record);
    } else {
        // if no matching record found, return 404 response code
        http_response_code(404);
    }

    // close statement and connection
    $stmt->close();
    $conn->close();
}else {
    // if id parameter is missing or empty, return 400 response code
    http_response_code(400);
}
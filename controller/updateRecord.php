<?php

include('../model/conn.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form data
    $id = $_POST['id'];
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $dob = $_POST['dob'];
    $contact = $_POST['contact'];
    $bodyTemp = $_POST['bodyTemp'];
    $covidDiagnosed = $_POST['covidDiagnosed'];
    $covidEncounter = $_POST['covidEncounter'];
    $vaccinated = $_POST['vaccinated'];
    $nationality = $_POST['nationality'];

    // Sanitize form data to prevent SQL injection
    $id = mysqli_real_escape_string($conn, $id);
    $firstname = mysqli_real_escape_string($conn, $firstname);
    $lastname = mysqli_real_escape_string($conn, $lastname);
    $dob = mysqli_real_escape_string($conn, $dob);
    $contact = mysqli_real_escape_string($conn, $contact);
    $bodyTemp = mysqli_real_escape_string($conn, $bodyTemp);
    $covidDiagnosed = mysqli_real_escape_string($conn, $covidDiagnosed);
    $covidEncounter = mysqli_real_escape_string($conn, $covidEncounter);
    $vaccinated = mysqli_real_escape_string($conn, $vaccinated);
    $nationality = mysqli_real_escape_string($conn, $nationality);

    // Insert record into database
    $query = "UPDATE records SET 
        firstname = '$firstname', 
        lastname = '$lastname', 
        dob = '$dob', 
        contact = '$contact', 
        bodyTemp = '$bodyTemp', 
        covidDiagnosed = '$covidDiagnosed', 
        covidEncounter = '$covidEncounter', 
        vaccinated = '$vaccinated', 
        nationality = '$nationality'
    WHERE id = $id";
    mysqli_query($conn, $query);

    // Return success message
    $response = array('status' => 'success');
    echo json_encode($response);

}
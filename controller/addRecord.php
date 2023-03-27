<?php
    include('../model/conn.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form data
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $gender = $_POST['gender'];
    $dob = $_POST['dob'];
    $contact = $_POST['contact'];
    $bodyTemp = $_POST['bodyTemp'];
    $covidDiagnosed = $_POST['covidDiagnosed'];
    $covidEncounter = $_POST['covidEncounter'];
    $vaccinated = $_POST['vaccinated'];
    $nationality = $_POST['nationality'];

    // Sanitize form data to prevent SQL injection
    $firstname = mysqli_real_escape_string($conn, $firstname);
    $lastname = mysqli_real_escape_string($conn, $lastname);
    $gender = mysqli_real_escape_string($conn, $gender);
    $dob = mysqli_real_escape_string($conn, $dob);
    $contact = mysqli_real_escape_string($conn, $contact);
    $bodyTemp = mysqli_real_escape_string($conn, $bodyTemp);
    $covidDiagnosed = mysqli_real_escape_string($conn, $covidDiagnosed);
    $covidEncounter = mysqli_real_escape_string($conn, $covidEncounter);
    $vaccinated = mysqli_real_escape_string($conn, $vaccinated);
    $nationality = mysqli_real_escape_string($conn, $nationality);

    // Insert record into database
    $query = "INSERT INTO records (firstname, lastname, gender, dob, contact, bodyTemp, covidDiagnosed, covidEncounter, vaccinated, nationality) VALUES ('$firstname', '$lastname', '$gender', '$dob', '$contact', '$bodyTemp', '$covidDiagnosed', '$covidEncounter', '$vaccinated', '$nationality')";
    mysqli_query($conn, $query);

    // Return success message
    $response = array('status' => 'success');
    echo json_encode($response);

}

?>
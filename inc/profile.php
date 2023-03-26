<?php

// Add your database connection code here
// Connect to the database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "covid_records";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

function addRecord($conn) {
    // Retrieve form data
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
    $query = "INSERT INTO records (firstname, lastname, dob, contact, bodyTemp, covidDiagnosed, covidEncounter, vaccinated, nationality) VALUES ('$firstname', '$lastname', '$dob', '$contact', '$bodyTemp', '$covidDiagnosed', '$covidEncounter', '$vaccinated', '$nationality')";
    mysqli_query($conn, $query);

    // Return success message
    $response = array('status' => 'success', 'message' => 'Record added successfully');
    echo json_encode($response);
}

// Call addRecord method if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    addRecord($conn);
}

?>

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
?>
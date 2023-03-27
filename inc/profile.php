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
function retrieveData($conn){
    // SQL server connection information
    $sql_details = array(
        'user' => 'root',
        'pass' => '',
        'model'   => 'covid_records',
        'host' => 'localhost'
        // ,'charset' => 'utf8' // Depending on your PHP and MySQL config, you may need this
    );
    // DB table to use
    $table = 'records';

    // Table's primary key
    $primaryKey = 'id';

    $columns = array(
        array(
            'model' => 'id',
            'dt' => 0,
            'formatter' => function( $d, $row ) {
                return '<a href="#updateRecord">'.str_pad($d,4,0,STR_PAD_LEFT).'</a>';
            }
        ),
        array('model' => 'firstname', 'dt' => 1),
        array('model' => 'lastname', 'dt' => 2),
        array(
            'model' => 'dob',
            'dt' => 3,
            'formatter' => function( $d, $row ) {
                return calculateAge($d);
            }
        ),
        array('model' => 'contact', 'dt' => 4),
        array('model' => 'bodyTemp', 'dt' => 5),
        array('model' => 'covidDiagnosed', 'dt' => 6),
        array('model' => 'covidEncounter', 'dt' => 7),
        array('model' => 'vaccinated', 'dt' => 8),
        array('model' => 'nationality', 'dt' => 9),
        //array('model' => 'CONCAT(firstname, " ", lastname) AS full_name', 'dt' => 9)
    );

    require( 'ssp.class.php' );
    $sql_select = "SELECT ".implode(", ", SSP::pluck($columns, 'model'))." ";
    $sql_details['select'] = $sql_select;

    echo json_encode(
        SSP::simple( $_GET, $sql_details, $table, $primaryKey, $columns )
    );
}

function calculateAge($dob) {
    $birthdate = new DateTime($dob);
    $today = new DateTime();
    $age = $today->diff($birthdate)->y;
    return $age;
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

retrieveData($conn);
// Call addRecord method if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    addRecord($conn);

}

// Call retrieveData method if server-side call is made
else if($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['get_data'])) {
    retrieveData($conn);
}

?>

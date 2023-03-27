<?php
// Define the database connection parameters
include('../model/conn.php');

// Define the SQL queries to retrieve the data
$vaccinatedQuery = "
    SELECT 
      SUM(CASE WHEN YEAR(CURDATE()) - YEAR(dob) < 10 THEN 1 ELSE 0 END) AS below10,
      SUM(CASE WHEN YEAR(CURDATE()) - YEAR(dob) BETWEEN 11 AND 20 THEN 1 ELSE 0 END) AS age11_20,
      SUM(CASE WHEN YEAR(CURDATE()) - YEAR(dob) BETWEEN 21 AND 30 THEN 1 ELSE 0 END) AS age21_30,
      SUM(CASE WHEN YEAR(CURDATE()) - YEAR(dob) BETWEEN 31 AND 40 THEN 1 ELSE 0 END) AS age31_40,
      SUM(CASE WHEN YEAR(CURDATE()) - YEAR(dob) BETWEEN 41 AND 50 THEN 1 ELSE 0 END) AS age41_50,
      SUM(CASE WHEN YEAR(CURDATE()) - YEAR(dob) BETWEEN 51 AND 60 THEN 1 ELSE 0 END) AS age51_60,
      SUM(CASE WHEN YEAR(CURDATE()) - YEAR(dob) BETWEEN 61 AND 70 THEN 1 ELSE 0 END) AS age61_70,
      SUM(CASE WHEN YEAR(CURDATE()) - YEAR(dob) BETWEEN 71 AND 80 THEN 1 ELSE 0 END) AS age71_80,
      SUM(CASE WHEN YEAR(CURDATE()) - YEAR(dob) > 80 THEN 1 ELSE 0 END) AS above80
    FROM 
      records WHERE vaccinated = 'Yes'
";
$diagnosedQuery = "
    SELECT 
      SUM(CASE WHEN YEAR(CURDATE()) - YEAR(dob) < 10 THEN 1 ELSE 0 END) AS below10,
      SUM(CASE WHEN YEAR(CURDATE()) - YEAR(dob) BETWEEN 11 AND 20 THEN 1 ELSE 0 END) AS age11_20,
      SUM(CASE WHEN YEAR(CURDATE()) - YEAR(dob) BETWEEN 21 AND 30 THEN 1 ELSE 0 END) AS age21_30,
      SUM(CASE WHEN YEAR(CURDATE()) - YEAR(dob) BETWEEN 31 AND 40 THEN 1 ELSE 0 END) AS age31_40,
      SUM(CASE WHEN YEAR(CURDATE()) - YEAR(dob) BETWEEN 41 AND 50 THEN 1 ELSE 0 END) AS age41_50,
      SUM(CASE WHEN YEAR(CURDATE()) - YEAR(dob) BETWEEN 51 AND 60 THEN 1 ELSE 0 END) AS age51_60,
      SUM(CASE WHEN YEAR(CURDATE()) - YEAR(dob) BETWEEN 61 AND 70 THEN 1 ELSE 0 END) AS age61_70,
      SUM(CASE WHEN YEAR(CURDATE()) - YEAR(dob) BETWEEN 71 AND 80 THEN 1 ELSE 0 END) AS age71_80,
      SUM(CASE WHEN YEAR(CURDATE()) - YEAR(dob) > 80 THEN 1 ELSE 0 END) AS above80
    FROM 
      records WHERE covidDiagnosed = 'Yes'
";
$encounterQuery = "
    SELECT 
      SUM(CASE WHEN YEAR(CURDATE()) - YEAR(dob) < 10 THEN 1 ELSE 0 END) AS below10,
      SUM(CASE WHEN YEAR(CURDATE()) - YEAR(dob) BETWEEN 11 AND 20 THEN 1 ELSE 0 END) AS age11_20,
      SUM(CASE WHEN YEAR(CURDATE()) - YEAR(dob) BETWEEN 21 AND 30 THEN 1 ELSE 0 END) AS age21_30,
      SUM(CASE WHEN YEAR(CURDATE()) - YEAR(dob) BETWEEN 31 AND 40 THEN 1 ELSE 0 END) AS age31_40,
      SUM(CASE WHEN YEAR(CURDATE()) - YEAR(dob) BETWEEN 41 AND 50 THEN 1 ELSE 0 END) AS age41_50,
      SUM(CASE WHEN YEAR(CURDATE()) - YEAR(dob) BETWEEN 51 AND 60 THEN 1 ELSE 0 END) AS age51_60,
      SUM(CASE WHEN YEAR(CURDATE()) - YEAR(dob) BETWEEN 61 AND 70 THEN 1 ELSE 0 END) AS age61_70,
      SUM(CASE WHEN YEAR(CURDATE()) - YEAR(dob) BETWEEN 71 AND 80 THEN 1 ELSE 0 END) AS age71_80,
      SUM(CASE WHEN YEAR(CURDATE()) - YEAR(dob) > 80 THEN 1 ELSE 0 END) AS above80
    FROM 
      records WHERE covidEncounter = 'Yes'
";

$countryQuery = "SELECT nationality, COUNT(*) AS count FROM records GROUP BY nationality";

$allQuery = "SELECT COUNT(*) AS record_count FROM records";
$allVaccinatedQuery = "SELECT COUNT(*) AS vaccinated_count FROM records WHERE vaccinated='Yes'";
$allEncounterQuery = "SELECT COUNT(*) AS covid_encounter_count FROM records WHERE covidEncounter='Yes'";
$allFeverQuery = "SELECT COUNT(*) AS high_temp_count FROM records WHERE bodyTemp >= 38";

// Execute each query and retrieve the data
$vaccinatedResult = $conn->query($vaccinatedQuery);
$diagnosedResult = $conn->query($diagnosedQuery);
$encounterResult = $conn->query($encounterQuery);
//$countryResult = $conn->query($countryQuery);

$allResult = $conn->query($allQuery);
$allVaccinatedResult = $conn->query($allVaccinatedQuery);
$allEncounterResult = $conn->query($allEncounterQuery);
$allFeverResult = $conn->query($allFeverQuery);


// Extract the data from each result set
$vaccinatedData = $vaccinatedResult->fetch_assoc();
$diagnosedData = $diagnosedResult->fetch_assoc();
$encounterData = $encounterResult->fetch_assoc();
//$countryData = $countryResult->fetch_all();

$allData = $allResult->fetch_assoc();
$allVaccinatedData = $allVaccinatedResult->fetch_assoc();
$allEncounterData = $allEncounterResult->fetch_assoc();
$allFeverData = $allFeverResult->fetch_assoc();

// Close the database connection
$conn->close();

// Define the response as an associative array
$response = array(
    "vaccinatedData" => $vaccinatedData,
    "diagnosedData" => $diagnosedData,
    "encounterData" => $encounterData,
//    "countryData" => $countryData,
    "allData" => $allData,
    "allVaccinatedData" => $allVaccinatedData,
    "allEncounterData" => $allEncounterData,
    "allFeverData" => $allFeverData,
);

// Set the response header to indicate that the content type is JSON
header('Content-Type: application/json');

// Encode the response as JSON and print it to the screen
echo json_encode($response);
?>
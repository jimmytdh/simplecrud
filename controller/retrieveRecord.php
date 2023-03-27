<?php

include('../model/conn.php');

if($_SERVER['REQUEST_METHOD'] === 'GET') {
    // SQL server connection information
    $sql_details = array(
        'user' => $username,
        'pass' => $password,
        'model'   => $dbname,
        'host' => $servername
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
                return '<a href="#" class="updateRecord" data-id="'.$d.'">'.str_pad($d,4,0,STR_PAD_LEFT).'</a>';
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
        array(
            'model' => 'id',
            'dt' => 10,
            'formatter' => function( $d, $row ) {
                return '<a href="#" class="deleteRecord btn btn-outline-danger btn-sm" data-id="'.$d.'"><i class="bi bi-x"></i> Remove</a>';
            }
        ),
        //array('model' => 'CONCAT(firstname, " ", lastname) AS full_name', 'dt' => 9)
    );

    require( '../inc/ssp.class.php' );
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
?>
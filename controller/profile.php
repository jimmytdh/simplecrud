<?php

    class Profile {
        //database and table name
        private $conn;
        private $table = 'records';

        //columns
        public $id;
        public $firstname;
        public $lastname;
        public $dob;
        public $gender;
        public $contact;
        public $bodyTemp;
        public $covidDiagnosed;
        public $covidEncounter;
        public $vaccinated;
        public $nationality;

        public function __construct($db){
            $this->conn = $db;
        }

        public function cardData(){
            //query
            $sql = "SELECT
                COUNT(*) as record_count,
                COUNT(IF(vaccinated = 'Yes', 1, NULL)) AS vaccinated_count,  
                COUNT(IF(covidEncounter = 'Yes', 1, NULL)) AS covid_encounter_count,  
                COUNT(IF(bodyTemp >= 38, 1, NULL)) AS high_temp_count,  
                COUNT(IF(gender = 'Male', 1, NULL)) AS male_count,  
                COUNT(IF(gender = 'Female', 1, NULL)) AS female_count  
                FROM records                           
            ";
            $result = mysqli_query($this->conn,$sql);
            $result = $result->fetch_assoc();
            return $result;
        }

        public function chartData($param = null){
            //query
            $sql = "
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
                  records
            ";

            if($param){
                $sql .= " WHERE $param = 'Yes'";
            }
            $result = mysqli_query($this->conn,$sql);
            $result = $result->fetch_assoc();
            return $result;
        }

        public function getByID($id){
            //sanitize id
            $id = filter_var($id,FILTER_SANITIZE_NUMBER_INT);

            //query
            $sql = "SELECT * FROM $this->table WHERE id=$id";
            $result = mysqli_query($this->conn, $sql);
            mysqli_close($this->conn);
            return $result->fetch_assoc();
        }

        function calculateAge($dob) {
            $birthdate = new DateTime($dob);
            $today = new DateTime();
            $age = $today->diff($birthdate)->y;
            return $age;
        }

        //get all data using dataTable
        public function getAll(){
            // SQL server connection information for DataTable
            $sql_details = array(
                'user' => DB_USERNAME,
                'pass' => DB_PASSWORD,
                'model'   => DB_NAME,
                'host' => DB_HOST
                // ,'charset' => 'utf8' // Depending on your PHP and MySQL config, you may need this
            );

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
                        return $this->calculateAge($d);
                    }
                ),
                array('model' => 'gender', 'dt' => 4),
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
                SSP::simple( $_GET, $sql_details, $this->table, $primaryKey, $columns )
            );
        }

        public function store(){
            $this->firstname = htmlspecialchars(strip_tags($this->firstname));
            $this->lastname = htmlspecialchars(strip_tags($this->lastname));
            $this->gender = htmlspecialchars(strip_tags($this->gender));
            $this->dob = htmlspecialchars(strip_tags($this->dob));
            $this->contact = htmlspecialchars(strip_tags($this->contact));
            $this->bodyTemp = htmlspecialchars(strip_tags($this->bodyTemp));
            $this->covidDiagnosed = htmlspecialchars(strip_tags($this->covidDiagnosed));
            $this->covidEncounter = htmlspecialchars(strip_tags($this->covidEncounter));
            $this->vaccinated = htmlspecialchars(strip_tags($this->vaccinated));
            $this->nationality = htmlspecialchars(strip_tags($this->nationality));

            // Insert record into database
            $query = "INSERT INTO records (firstname, lastname, gender, dob, contact, bodyTemp, covidDiagnosed, covidEncounter, vaccinated, nationality) VALUES ('$this->firstname', '$this->lastname', '$this->gender', '$this->dob', '$this->contact', '$this->bodyTemp', '$this->covidDiagnosed', '$this->covidEncounter', '$this->vaccinated', '$this->nationality')";
            if(mysqli_query($this->conn, $query)){
                return true;
            }
            return false;
        }

        public function update(){
            $this->id = htmlspecialchars(strip_tags($this->id));
            $this->firstname = htmlspecialchars(strip_tags($this->firstname));
            $this->lastname = htmlspecialchars(strip_tags($this->lastname));
            $this->gender = htmlspecialchars(strip_tags($this->gender));
            $this->dob = htmlspecialchars(strip_tags($this->dob));
            $this->contact = htmlspecialchars(strip_tags($this->contact));
            $this->bodyTemp = htmlspecialchars(strip_tags($this->bodyTemp));
            $this->covidDiagnosed = htmlspecialchars(strip_tags($this->covidDiagnosed));
            $this->covidEncounter = htmlspecialchars(strip_tags($this->covidEncounter));
            $this->vaccinated = htmlspecialchars(strip_tags($this->vaccinated));
            $this->nationality = htmlspecialchars(strip_tags($this->nationality));

            // Insert record into database
            $query = "UPDATE records SET 
                firstname = '$this->firstname', 
                lastname = '$this->lastname', 
                gender = '$this->gender', 
                dob = '$this->dob', 
                contact = '$this->contact', 
                bodyTemp = '$this->bodyTemp', 
                covidDiagnosed = '$this->covidDiagnosed', 
                covidEncounter = '$this->covidEncounter', 
                vaccinated = '$this->vaccinated', 
                nationality = '$this->nationality'
            WHERE id = $this->id";
            if(mysqli_query($this->conn, $query)){
                mysqli_close($this->conn);
                return true;
            }

            return true;
        }

        public function delete(){
            // get the id value and sanitize it
            $id = filter_var($this->id, FILTER_SANITIZE_NUMBER_INT);

            // SQL delete statement
            $sql = "DELETE FROM records WHERE id=$id";

            // execute the delete statement
            if (mysqli_query($this->conn, $sql)) {
                mysqli_close($this->conn);
                return true;
            }
            return false;

        }
    }
?>
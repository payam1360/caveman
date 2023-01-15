<?php

define("DBG", false);
define("MAX_cnt", 2);


function validateUserCredentials($userInfo) {
       
    $servername  = "127.0.0.1";
    $loginname   = "root";
    $password    = "@Ssia123";
    $dbname      = "Users";
    $tablename   = "authentication";
    $result      = -1;
    // Create connection
    $conn        = new mysqli($servername, $loginname, $password, $dbname);
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    if(DBG) {
        echo "Connected successfully";
    }
    // first check if the username exists:
    
    $sql = "SELECT password " . "FROM " . $tablename . " WHERE username = '" . $userInfo[0]->qAnswer . "';";
    if(DBG) {
        echo $sql;
    }
    $password = $conn->query($sql);
    // user already registered, get the corresponding password
    if($password->num_rows == 0 ) {
        $result = 2; // user is not registered
        if (DBG) {
            echo "user not registered";
        }
    }
    else if($userInfo[1]->qAnswer === $password->fetch_column(0)) {
        $result = 0; // user name password are ok
        if (DBG) {
            echo "user exists and ok";
        }
    } else {
        $result = 1; // user name password are wrong
        if (DBG) {
            echo "user exists but password wrong";
        }
    }

    $conn->close();
    return $result;
}


function dataPrep($ok){
    if($ok === 0) {
        $data = array('flag' => 0);
        }
    else if($ok === 1) {
        $data = array('flag' => 1);
        }
    else if($ok === 2) {
        $data = array('flag' => 2);
    }
    return $data;
}

/// -------------------------
/// main routin starts here.
/// -------------------------
$userdata      = json_decode($_POST['userInfo']);
$dbflag        = validateUserCredentials($userdata);
$data          = dataPrep($dbflag);
echo json_encode($data);


?>


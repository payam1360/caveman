<?php

define("DBG", false);
define("MAX_cnt", 2);


function registerUserCredentials($userInfo) {
       
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
    
    $sql = "INSERT INTO " . $tablename . " (password, username) VALUES('" . $userInfo[1]->answer . "','" .  $userInfo[0]->answer . "');";
    if(DBG) {
        echo $sql;
    }
    
    // user already registered, get the corresponding password
    if($conn->query($sql) === TRUE) {
        $result = 0; // user is not registered
        if (DBG) {
            echo "user is registered";
        }
    }
    else  {
        $result = 1; // user name password are ok
        if (DBG) {
            echo "user is not registered";
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
    return $data;
}

/// -------------------------
/// main routin starts here.
/// -------------------------
$userdata      = json_decode($_POST['userInfo']);
$dbflag        = registerUserCredentials($userdata);
$data          = dataPrep($dbflag);
echo json_encode($data);


?>


<?php

define("DBG", false);
define("MAX_cnt", 2);


function validateUserCredentials($userInfo) {
       
    $servername  = "127.0.0.1";
    $loginname   = "root";
    $password    = "@Ssia123";
    $dbname      = "Users";
    $tablename   = "authentication";
    $result['status']   = -1;
    $result['userName'] = '';
    $result['userId']   = '';

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
    $password = $conn->query($sql);

    if(DBG) {
        echo $sql;
    }
    
    // user already registered, get the corresponding password
    if($password->num_rows == 0 ) {
        $result['status']   = 2; // user is not registered
        $result['userName'] = '';
        $result['userId']   = '';
        if (DBG) {
            echo "user not registered";
        }
    }
    else if($userInfo[1]->qAnswer === $password->fetch_column(0)) {
        $sql = "SELECT userId " . "FROM " . $tablename . " WHERE username = '" . $userInfo[0]->qAnswer . "';";
        $userId = $conn->query($sql);
        $result['status']   = 0; // user is not registered
        $result['userName'] = $userInfo[0]->qAnswer;
        $result['userId']   = $userId->fetch_column(0);
        if (DBG) {
            echo "user exists and ok";
        }
    } else {
        $result['status']   = 1; // user is not registered
        $result['userName'] = '';
        $result['userId']   = '';
        if (DBG) {
            echo "user exists but password wrong";
        }
    }

    $conn->close();
    return $result;
}



/// -------------------------
/// main routin starts here.
/// -------------------------
$userdata      = json_decode($_POST['userInfo']);
$data          = validateUserCredentials($userdata);

if($data['status'] == 0) {
    session_start();
    $_SESSION['userName'] = $data['userName'];
    $_SESSION['userId'] = $data['userId'];
}
echo json_encode($data);


?>


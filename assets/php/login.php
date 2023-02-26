<?php

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
    $sql = "SELECT password " . "FROM " . $tablename . " WHERE email = '" . $userInfo[0]->qAnswer . "';";
    $password = $conn->query($sql);
    // user already registered, get the corresponding password
    if($password->num_rows == 0 ) {
        $result['status']   = 2; // user is not registered
        $result['userName'] = '';
        $result['userId']   = '';
    }
    else {
        if(password_verify($userInfo[1]->qAnswer, $password->fetch_column(0))) {
            $sql = "SELECT userId, name " . "FROM " . $tablename . " WHERE email = '" . $userInfo[0]->qAnswer . "';";
            $userId = $conn->query($sql);
            $data = $userId->fetch_assoc();
            $result['userName'] = $data['name'];
            $result['userId']   = $data['userId'];
            $result['status']   = 0; // log in verified
        } else {
            $result['status']   = 1; // wrong password
            $result['userName'] = '';
            $result['userId']   = '';
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


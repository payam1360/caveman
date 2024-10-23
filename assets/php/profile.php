<?php


function changePassWord($conn, $userId, $password){
    
    $oldPass    = $password[0];
    $newPass    = $password[1];
    $newPassRe  = $password[2];
    $tablename   = 'authentication';
    $sql         = "SELECT password FROM $tablename;";
    $dbOut       = $conn->query($sql);
    $dbOut       = $dbOut->fetch_assoc();
    if(password_verify($oldPass, $dbOut['password'])) {
        $data['status'] = 0;
    } else {
        $data['status'] = 1;
    }
    if($data['status'] == 0) {
        if($newPass === $newPassRe) {
            $hash        = password_hash($newPass, PASSWORD_DEFAULT);
            $sql         = "UPDATE " . $tablename . " SET password = '" . $hash . "' WHERE userId = '" . $userId . "';";
            $conn->query($sql);
        } else {
            $data['status'] = 2;
        }
    }
    return $data;
}



function getProfileInfo($conn, $userId) {

    $tablename        = 'authentication';
    $sql              = "SELECT * FROM $tablename WHERE userId = '$userId';";
    $db_out           = $conn->query($sql);
    $data             = $db_out->fetch_assoc();
    unset($data['userStripeId']);
    unset($data['userStripeToken']);
    unset($data['password']);
    unset($data['verification']);
    unset($data['emailVer']);
    unset($data['passVer']);
    unset($data['payVer']);
    return $data;
}

function saveProfileInfo($conn, $userId, $data) {
    $tablename    = 'authentication';
    $name         = $data->name;
    $facebook     = $data->facebook;
    $instagram    = $data->insta;
    $twitter      = $data->twitter;
    $linkedIn     = $data->linkedIn;
    $jobTitle     = $data->jobTitle;
    $phoneNumber  = $data->phoneNumber;
    $email        = $data->email;
    $updates = [];
    if (!empty($name)) {
        $updates[] = "name = '$name'";
    }
    if (!empty($email)) {
        $updates[] = "email = '$email'";
    }
    if (!empty($phoneNumber)) {
        $updates[] = "phoneNumber = '$phoneNumber'";
    }
    if (!empty($jobTitle)) {
        $updates[] = "jobTitle = '$jobTitle'";
    }
    if (!empty($facebook)) {
        $updates[] = "facebook = '$facebook'";
    }
    if (!empty($instagram)) {
        $updates[] = "instagram = '$instagram'";
    }
    if (!empty($twitter)) {
        $updates[] = "twitter = '$twitter'";
    }
    if (!empty($linkedIn)) {
        $updates[] = "linkedIn = '$linkedIn'";
    }

    // Join the updates to form the SET clause
    $setClause = implode(', ', $updates);

    if (!empty($setClause)) {
        $sql = "UPDATE $tablename SET $setClause WHERE userId = '$userId';";
        // Execute the query
    }
    $db_out       = $conn->query($sql);
    return true;
}


function updateAccountType($conn, $userId, $accountType) {
    $tablename = 'authentication';
    $sql       = "UPDATE $tablename SET accountType = '$accountType' WHERE userId = '$userId';";
    $db_out    = $conn->query($sql);
    return true;
}

function verifyPayment($conn, $userId) {
    $tablename = 'authentication';
    $sql       = "SELECT payVer FROM $tablename WHERE userId = '$userId';";
    $db_out    = $conn->query($sql);
    $db_out    = $db_out->fetch_assoc();
    $db_out    = $db_out['payVer'];
    if($db_out == '1') {
        $data['status'] = false;
    } else {
        $data['status'] = true;
    }
    return $data['status'];
}

// Replace these with your actual credentials
$userInfo      = json_decode($_POST['userInfo']);
// server connect
$servername  = "127.0.0.1";
$loginname   = "root";
$password    = "@Ssia123";
$dbname      = "Users";
$conn        = new mysqli($servername, $loginname, $password, $dbname);
// --------
if($userInfo->flag == 'changePass') {
    $response = changePassWord($conn, $userInfo->userId, $userInfo->password);
} elseif($userInfo->flag == 'saveProfile'){
    $response = saveProfileInfo($conn, $userInfo->userId, $userInfo->input);
} elseif($userInfo->flag == 'getProfile'){
    $response = getProfileInfo($conn, $userInfo->userId);
} elseif($userInfo->flag == 'updateAccountType') {
    $response = updateAccountType($conn, $userInfo->userId, $userInfo->accountType);
} elseif($userInfo->flag == 'verifyPayment') {
    $response = verifyPayment($conn, $userInfo->userId);
}
$conn->close();
echo json_encode($response);

?>

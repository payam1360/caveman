<?php

// adding the client for a user. The user ID is coming from the global SESSION 
// This is initial client addition for a given nutritionist.
function addClient($userId, $Data) {
    
    $servername   = "127.0.0.1";
    $loginname    = "root";
    $password     = "@Ssia123";
    $dbname       = "Users";
    $tablename    = "userAllocation";
    
    $name         = $Data[0]->qAnswer;
    $gender       = $Data[1]->qAnswer;
    $goal         = $Data[2]->qAnswer;
    $nutritionEng = $Data[3]->qAnswer;
    $mealEng      = $Data[4]->qAnswer;
    // Create connection
    $conn         = new mysqli($servername, $loginname, $password, $dbname);
    // check if the client exists.
    $sql          = "UPDATE $tablename SET name = '$name', 
                                           gender = '$gender', 
                                           goal = '$goal', 
                                           nutritionEng = '$nutritionEng', 
                                           mealEng = '$mealEng' 
                                           WHERE 
                                           userId = '$userId' 
                                           AND 
                                           used = '0' 
                                           AND 
                                           completed = '0' 
                                           AND 
                                           gender = '' 
                                           LIMIT 1;";
    $out = $conn->query($sql);
    $userInfo['status'] = $out;
    return $userInfo;
}


/// -------------------------
/// main routin starts here.
/// -------------------------
$userdata      = json_decode($_POST['userInfo']);
session_start();
$userId        = $_SESSION['userId'];
$userInfo      = addClient($userId, $userdata);
echo json_encode($userInfo);


?>



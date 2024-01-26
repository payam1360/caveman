<?php

function saveUserComment($clientId, $userId, $comment, $category) {
    
    $servername   = "127.0.0.1";
    $loginname    = "root";
    $password     = "@Ssia123";
    $dbname       = "Users";
    $tablename    = "userAllocation";
    // Create connection
    $conn         = new mysqli($servername, $loginname, $password, $dbname);
    // check if the client exists.
    if($category == 'bmi') {
        $field = 'descBmi';
    } elseif($category == 'macro') {
        $field = 'descMacro';
    }
    $sql = "UPDATE $tablename SET 
                                            $field = '$comment'  
                                            WHERE 
                                            userId = '$userId' 
                                            AND 
                                            clientId = '$clientId';"; 
    $database_out = $conn->query($sql);
    return 0;
    
}




/// -------------------------
/// main routin starts here.
/// -------------------------
$userdata = json_decode($_POST['userInfo']);
$category = $userdata->topic; 
$comment  = $userdata->clientText; 
$userId   =  $userdata->userId;
$clientId = $userdata->clientId;
$status   = saveUserComment($clientId, $userId, $comment, $category);
echo json_encode($status);


?>

<?php

define("DBG", false);

function saveUserDataIntoDB($Questions) {
       
    $servername  = "127.0.0.1";
    $loginname   = "root";
    $password    = "@Ssia123";
    $dbname      = "Users";
    $tablename   = "Users";
    $flag        = false;
    // Create connection
    $conn        = new mysqli($servername, $loginname, $password, $dbname);
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    if(DBG) {
        echo "Connected successfully";
    }
    
    // get the user ID
    session_start();
    $userId = $_SESSION['userId'];
    // get question content:
    $qContent = $Questions[1]->qAnswer;
    // question answer is empty
    $qAnswer = "";
    // get question index (Not sure what to do here) FIXME
    $qIdx = 0;
    // get question type
    if($Questions[0]->qAnswer == 0) {
        $qType = "list";
    } else if($Questions[0]->qAnswer == 1) {
        $qType = "text";
    } else if($Questions[0]->qAnswer == 2) {
        $qType = "button";
    } else if($Questions[0]->qAnswer == 3) {
        $qType = "email";
    }
    // visited field
    $visited = 0;
    // required field
    $qRequired = $Questions[2]->qAnswer;
    // get the options and options texts parsing the user text input response;
    if($qType == "button") {
        $options = $Questions[3]->qAnswer;
        $optionsText = $Questions[4]->qAnswer;
    } else if($qType == "list") {
        $options = $Questions[3]->qAnswer;
        $optionsText = "";
    } else if($qType == "text") {
        $options = "";
        $optionsText = "";
    } else if($qType == "email") {
        $options = "";
        $optionsText = "";
    }
    
    // set campaignId
    if($qIdx == 0) {
        $campaignId = mt_rand();
        $campaignTime = date("Y-m-d", time());
    }
    // set clientId
    if($qIdx == 0) {
        $clientId = mt_rand();
    }
        
    $sql = "INSERT INTO " . $tablename . " (userId, clientId, campaignId, campaignTime, qIdx, qType, qContent, qAnswer, options, optionsText, visited, qRequired) VALUES('" . $userId . "','" . $clientId . "','" . $campaignId . "','" . $campaignTime . "','" . $qIdx . "','" . $qType . "','" . $qContent . "','" . $qAnswer . "','" . $options . "','" . $optionsText . "','" . $visited . "','" . $qRequired . "')";
    if(DBG) {
        echo $sql;
    }
    $flag = $conn->query($sql);
    if ($flag == true and DBG) {
        echo "New record created successfully";
    } elseif(DBG || $flag == false) {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
    $conn->close();
    return $flag;
}

/// -------------------------
/// main routin starts here.
/// -------------------------
$userdata       = json_decode($_POST['userInfo']);
$flag           = saveUserDataIntoDB($userdata);
if($flag == true) {
    $data['status'] = 0;
} else {
    $data['status'] = 1;
}
if($data['status'] == 1 and DBG) {
    echo "user has not registered. no data is saved";
}elseif(DBG){
    echo "user data is saved.\n";
}
echo json_encode($data);

?>


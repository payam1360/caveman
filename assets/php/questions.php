<?php


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
    $conn->query($sql);
    $conn->close();
}

/// -------------------------
/// main routin starts here.
/// -------------------------
$userdata       = json_decode($_POST['userInfo']);
if($userdata->data[0]->qAnswer == '') {
    $data['status'] = 0;
} elseif($userdata->data[0]->qAnswer == '1' && $userdata->counter == 3) {
    $data['status'] = 1;
} elseif($userdata->data[0]->qAnswer == '3' && $userdata->counter == 3) {
    $data['status'] = 2;
} elseif($userdata->data[0]->qAnswer == '0' && $userdata->counter == 3) {
    $data['status'] = 3;
} elseif($userdata->data[0]->qAnswer == '0' && $userdata->counter == 5) {
    $data['status'] = 4;
} elseif($userdata->data[0]->qAnswer == '0' && $userdata->counter == 6) {
    $data['status'] = 5;
} elseif($userdata->data[0]->qAnswer == '2' && $userdata->counter == 3) {
    $data['status'] = 6;
} elseif($userdata->data[0]->qAnswer == '2' && $userdata->counter == 5) {
    $data['status'] = 7;
} elseif($userdata->data[0]->qAnswer == '2' && $userdata->counter == 6) {
    $data['status'] = 8;
} else {
    $data['status'] = 9;
}
echo json_encode($data);

?>


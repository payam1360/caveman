<?php


function saveUserDataIntoDB($Questions, $qIdx) {
       
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
    // question answer is empty
    $qAnswer = "";
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
    if($qType != 'email') {
    // get question content:
        $qContent = $Questions[3]->qAnswer;
    } else {
        $qContent = 'Hey #nameRegister, what is your email address?';
    }
    // Keyword of the question
    $qKey = $Questions[4]->qAnswer;
    // visited field
    $visited = 0;
    // required field
    $qRequired = $Questions[2]->qAnswer;
    // get the options and options texts parsing the user text input response;
    if($qType == "button") {
        $options = $Questions[5]->qAnswer;
        $optionsText = $Questions[6]->qAnswer;
    } else if($qType == "list") {
        $options = $Questions[5]->qAnswer;
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
        
    $sql = "INSERT INTO $tablename (userId, clientId, campaignId, campaignTime, qIdx, qType, qContent, qAnswer, options, optionsText, visited, qRequired, qKey) VALUES('$userId','$clientId','$campaignId', '$campaignTime','$qIdx','$qType','$qContent','$qAnswer','$options','$optionsText','$visited','$qRequired','$qKey')";
    $conn->query($sql);
    $conn->close();
}

/// -------------------------
/// main routin starts here.
/// -------------------------
$userdata       = json_decode($_POST['userInfo']);
static $qIdx = 0;
if($userdata->data[0]->qAnswer == '0') {
    $data['MAX_cnt'] = 7;
} elseif($userdata->data[0]->qAnswer == '1') {
    $data['MAX_cnt'] = 6;
} elseif($userdata->data[0]->qAnswer == '2') {
    $data['MAX_cnt'] = 8;
} elseif($userdata->data[0]->qAnswer == '3') {
    $data['MAX_cnt'] = 3;
}

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

if($data['MAX_cnt']  == $userdata->counter && $userdata->data[$userdata->counter - 1]->qAnswer == 1 ) {
    $data['status'] = 10; // keep asking
    saveUserDataIntoDB($userdata->data, $qIdx);
    $qIdx++;
} elseif($data['MAX_cnt'] == $userdata->counter && $userdata->data[$userdata->counter - 1]->qAnswer == 0) {
    $data['status'] = 11; // End the form builder
    $data['MAX_cnt'] = 9;
    saveUserDataIntoDB($userdata->data, $qIdx);
}
echo json_encode($data);

?>


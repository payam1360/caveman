<?php


function buildClientPage($userId, $clientId, $campaignId){
    $servername  = "127.0.0.1";
    $loginname   = "root";
    $password    = "@Ssia123";
    $dbname      = "Users";
    $tablename   = "Users";
    $conn        = new mysqli($servername, $loginname, $password, $dbname);
    copy("../../template.html","../../userPages/$userId$clientId$campaignId.html");
    $fp = fopen("../../userPages/$userId$clientId$campaignId.html", 'a+');
    $text = "<p class='userId'>$userId</p>\n";
    fwrite($fp, $text);
    $text = "<p class='clientId'>$clientId</p>\n";
    fwrite($fp, $text);
    $text = "<p class='campaignId'>$campaignId</p>\n";
    fwrite($fp, $text);
    $text = "</body>\n";
    fwrite($fp, $text);
    $text = "</html>\n";
    fwrite($fp, $text);
    fclose($fp);
}



function saveUserDataIntoDB($Questions, $qIdx, $complete, $userId, $ip) {
       
    $servername  = "127.0.0.1";
    $loginname   = "root";
    $password    = "@Ssia123";
    $dbname      = "Users";
    $tablename   = "Users";
    $table1name  = "userAllocation";
    $flag        = false;
    // Create connection
    $conn        = new mysqli($servername, $loginname, $password, $dbname);
    // Check connection

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
    // get the clientId and campaignId
    $clientId = $Questions[0]->clientId;
    $campaignId = $Questions[0]->campaignId;
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
    if($options != "") {
        $options = implode(",", $options);
    }
    $campaignTime = date("Y-m-d");
    // first check if there is incomplete campaigns
    $sql = "SELECT used, completed 
                    FROM $table1name 
                    WHERE 
                    userId = '$userId' 
                    AND 
                    clientId = '$clientId' 
                    AND 
                    campaignId = '$campaignId';";
    $data = $conn->query($sql);
    $data = $data->fetch_assoc();
    if($data['used'] == 0 && $data['completed'] == 0 && $complete == 0) { // start
        $sql = "UPDATE $table1name SET used = '1' WHERE userId = '$userId' AND clientId = '$clientId' AND campaignId = '$campaignId';";
        $conn->query($sql);
        $sql = "INSERT INTO $tablename (userId, clientId, ip, campaignId, campaignTime, qIdx, qType, qContent, qAnswer, options, optionsText, visited, qRequired, qKey) VALUES('$userId','$clientId', '$ip', '$campaignId', '$campaignTime', '$qIdx', '$qType', '$qContent', '$qAnswer', '$options', '$optionsText', '$visited', '$qRequired', '$qKey')";
        $conn->query($sql);

    } elseif($data['used'] == 1 && $data['completed'] == 0 && $complete == 0) { // keep adding 
        $sql = "INSERT INTO $tablename (userId, clientId, ip, campaignId, campaignTime, qIdx, qType, qContent, qAnswer, options, optionsText, visited, qRequired, qKey) VALUES('$userId','$clientId', '$ip', '$campaignId', '$campaignTime', '$qIdx', '$qType', '$qContent', '$qAnswer', '$options', '$optionsText', '$visited', '$qRequired', '$qKey')";
        $conn->query($sql);

    } elseif($data['used'] == 1 && $data['completed'] == 0 && $complete == 1) { // last question to conclude the campaign
        $sql = "UPDATE $table1name SET used = '0', completed = '1' WHERE userId = '$userId' AND clientId = '$clientId' AND campaignId = '$campaignId';";
        $conn->query($sql);
        $sql = "INSERT INTO $tablename (userId, clientId, ip, campaignId, campaignTime, qIdx, qType, qContent, qAnswer, options, optionsText, visited, qRequired, qKey) VALUES('$userId','$clientId', '$ip', '$campaignId', '$campaignTime', '$qIdx', '$qType', '$qContent', '$qAnswer', '$options', '$optionsText', '$visited', '$qRequired', '$qKey')";
        $conn->query($sql);

    } elseif($data['used'] == 0 && $data['completed'] == 1 && $complete == 1) { // redesign the campaign for the given client, last entry
        $sql = "UPDATE $table1name SET used = '0', completed = '1' WHERE userId = '$userId' AND clientId = '$clientId' AND campaignId = '$campaignId';";
        $conn->query($sql);
        $sql = "UPDATE $tablename SET userId = '$userId', clientId = '$clientId', ip = '$ip', campaignId = '$campaignId', campaignTime = '$campaignTime', qType = '$qType', qContent = '$qContent', qAnswer = '$qAnswer', options = '$options', optionsText = '$optionsText', visited = '$visited', qRequired = '$qRequired', qKey = '$qKey' WHERE qIdx = '$qIdx';";
        $conn->query($sql);

    } elseif($data['used'] == 0 && $data['completed'] == 1 && $complete == 0) { // redesign the campaign for the given client
        $sql = "UPDATE $table1name SET used = '0', completed = '1' WHERE userId = '$userId' AND clientId = '$clientId' AND campaignId = '$campaignId';";
        $conn->query($sql);
        $sql = "UPDATE $tablename SET userId = '$userId', clientId = '$clientId', ip = '$ip', campaignId = '$campaignId', campaignTime = '$campaignTime', qType = '$qType', qContent = '$qContent', qAnswer = '$qAnswer', options = '$options', optionsText = '$optionsText', visited = '$visited', qRequired = '$qRequired', qKey = '$qKey' WHERE qIdx = '$qIdx';";
        $conn->query($sql);

    } elseif($data['used'] == 0 && $data['completed'] == 0 && $complete == 1) { // the use only designed 1 question for his client
        $sql = "UPDATE $table1name SET used = '0', completed = '1' WHERE userId = '$userId' AND clientId = '$clientId' AND campaignId = '$campaignId';";
        $conn->query($sql);
        $sql = "INSERT INTO $tablename (userId, clientId, ip, campaignId, campaignTime, qIdx, qType, qContent, qAnswer, options, optionsText, visited, qRequired, qKey) VALUES('$userId','$clientId', '$ip', '$campaignId', '$campaignTime', '$qIdx', '$qType', '$qContent', '$qAnswer', '$options', '$optionsText', '$visited', '$qRequired', '$qKey')";
        $conn->query($sql);

    } else {
        // undefined ... we should not be here technically.
    }
    $Info['clientId']   = $clientId;
    $Info['campaignId'] = $campaignId;
    $conn->close();
    return $Info;
}

function getRealIpAddr()
{
    if (!empty($_SERVER['HTTP_CLIENT_IP']))   //check ip from share internet
    {
      $ip=$_SERVER['HTTP_CLIENT_IP'];
    }
    elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))   //to check ip is pass from proxy
    {
      $ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
    }
    else
    {
      $ip=$_SERVER['REMOTE_ADDR'];
    }
    return $ip;
}
/// -------------------------
/// main routin starts here.
/// -------------------------
$userdata      = json_decode($_POST['userInfo']);
$ip            = getRealIpAddr();
// get the user ID
$userId         = $userdata->data[0]->userId;
// check the question type selected by the user (nutritionist)
if($userdata->data[0]->qAnswer == '0') {
    $data['MAX_cnt'] = 7;
} elseif($userdata->data[0]->qAnswer == '1') {
    $data['MAX_cnt'] = 6;
} elseif($userdata->data[0]->qAnswer == '2') {
    $data['MAX_cnt'] = 7;
} elseif($userdata->data[0]->qAnswer == '3') {
    $data['MAX_cnt'] = 4;
}

if($userdata->data[0]->qAnswer == '') {
    $data['status'] = 0;
} elseif($userdata->data[0]->qAnswer == '1' && $userdata->counter == 3) {
    $data['status'] = 1;
} elseif($userdata->data[0]->qAnswer == '1' && $userdata->counter == 5) {
    $data['status'] = 2;
} elseif($userdata->data[0]->qAnswer == '3' && $userdata->counter == 3) {
    $data['status'] = 3;
} elseif($userdata->data[0]->qAnswer == '0' && $userdata->counter == 3) {
    $data['status'] = 4;
} elseif($userdata->data[0]->qAnswer == '0' && $userdata->counter == 5) {
    $data['status'] = 5;
} elseif($userdata->data[0]->qAnswer == '0' && $userdata->counter == 6) {
    $data['status'] = 6;
} elseif($userdata->data[0]->qAnswer == '2' && $userdata->counter == 3) {
    $data['status'] = 7;
} elseif($userdata->data[0]->qAnswer == '2' && $userdata->counter == 5) {
    $data['status'] = 8;
} elseif($userdata->data[0]->qAnswer == '2' && $userdata->counter == 6) {
    $data['status'] = 9;
} else {
    $data['status'] = 10;
}

if($data['MAX_cnt']  == $userdata->counter && $userdata->data[$userdata->counter-1]->qAnswer == 1 ) {
    $data['status'] = 11; // keep asking
    saveUserDataIntoDB($userdata->data, $userdata->qIdx, 0, $userId, $ip);
} elseif($data['MAX_cnt'] == $userdata->counter && $userdata->data[$userdata->counter-1]->qAnswer == 0) {
    $data['status'] = 12; // End the form builder
    $Info = saveUserDataIntoDB($userdata->data, $userdata->qIdx, 1, $userId, $ip);
    buildClientPage($userId, $Info['clientId'], $Info['campaignId']);
}
echo json_encode($data);

?>


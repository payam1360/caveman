<?php

require_once "functions.php";
function extractUserInfo($userId) {
    
    $servername   = "127.0.0.1";
    $loginname    = "root";
    $password     = "@Ssia123";
    $dbname       = "Users";
    $table1name   = "userAllocation";
    $table2name   = "Users";
    // Create connection
    $conn         = new mysqli($servername, $loginname, $password, $dbname);
    // check if the client exists.
    $sql          = "SELECT * FROM $table1name WHERE userId = '$userId';";
    $database_out = $conn->query($sql);
    $ids         = array();
    $names       = array();
    $genders     = array();
    $goals       = array();
    $campaignids = array();
    $formFlag    = array();
    while($database_row = $database_out->fetch_assoc()) {
        // filling out info briefs
        array_push($names, $database_row['name']);
        array_push($ids, $database_row['clientId']);
        array_push($formFlag, $database_row['completed']);
        if($database_row['gender'] == 0) {
            array_push($genders, 'male');
        } elseif($database_row['gender'] == 1){
            array_push($genders, 'female');
        } else {
            array_push($genders, 'No response');
        }
        if($database_row['goal'] == 0) {
            array_push($goals, 'increase testosterone');
        } elseif($database_row['goal'] == 1) {
            array_push($goals, 'gain muscle');
        } elseif($database_row['goal'] == 2) {
            array_push($goals, 'lose weight');
        } else {
            array_push($goals, 'No response');
        }
        array_push($campaignids, $database_row['campaignId']);
    }
    
    $userInfo['names']       = $names;
    $userInfo['ids']         = $ids;
    $userInfo['genders']     = $genders;
    $userInfo['goals']       = $goals;
    $userInfo['campaignids'] = $campaignids;
    $userInfo['formWasCreated'] = $formFlag;

    return $userInfo;
}


function extractClientInfo($clientId, $userId) {
    
    $servername   = "127.0.0.1";
    $loginname    = "root";
    $password     = "@Ssia123";
    $dbname       = "Users";
    $tablename    = "Users";
    // Create connection
    $conn         = new mysqli($servername, $loginname, $password, $dbname);
    // check if the client exists.
    $sql          = "SELECT * FROM $tablename WHERE 
                                                    userId = '$userId' 
                                                    AND 
                                                    clientId = '$clientId';";
    $database_out = $conn->query($sql);
    $clientInfo = array();
    $kk = 0;
    while($database_row = $database_out->fetch_assoc()) {
        // filling out info briefs
        $qKey        = $database_row['qKey'];
        $qIdx        = $database_row['qIdx']; 
        $qAnswer     = $database_row['qAnswer'];
        $optionsText = $database_row['optionsText'];
        $optionText_array = explode(",", $optionsText);
        $temp        = "[";
        for($optionCnt = 0; $optionCnt < count($optionText_array); $optionCnt++){
            if($optionCnt != count($optionText_array) - 1) {
                $temp = $temp . "\"" . $optionText_array[$optionCnt] . "\",";
            } else {
                $temp = $temp . "\"" . $optionText_array[$optionCnt] . "\"";
            }
        }
        $temp = $temp . "]";
        
        $out = '{"qKey":        ["' . $qKey    . '"],' .
               ' "qIdx":        "'  . $qIdx    . '",' .
               ' "qAnswer":     "'  . $qAnswer . '",' .
               ' "optionsText": [' . $temp    . ']}';

        $clientInfo[$kk] =  json_decode($out);
        $kk++;
    }
    
    return $clientInfo;
}




/// -------------------------
/// main routin starts here.
/// -------------------------
$userdata      = json_decode($_POST['userInfo']);
$userInfo      = extractUserInfo($userdata->userId);
if($userdata->clientId != '') {
    $clientInfo    = extractClientInfo($userdata->clientId, $userdata->userId);
    $user_bmi      = calculateBmi($clientInfo);
    $user_bmr      = calculateBmr($clientInfo);
    $user_if       = calculateIf($clientInfo);
    $user_macro    = calculateMacro($clientInfo);
    $user_micro    = calculateMicro($clientInfo);
    $output        = dataPrep($user_bmi, $user_bmr, $user_if, $user_macro, $user_micro);

} else {
    $output = '';
}
$userInfo['client'] = $output;
echo json_encode($userInfo);


?>



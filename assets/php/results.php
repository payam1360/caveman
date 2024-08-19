<?php

require_once "functions.php";
function extractUserInfo($userId) {
    
    $servername   = "127.0.0.1";
    $loginname    = "root";
    $password     = "@Ssia123";
    $dbname       = "Users";
    $table1name   = "userAllocation";
    $table2name   = "Users";
    $table3name   = "authentication";
    // Create connection
    $conn         = new mysqli($servername, $loginname, $password, $dbname);
    // check if the client exists.
    $sql          = "SELECT accountType FROM $table3name WHERE userId = '$userId';";
    $r  = $conn->query($sql);
    $accountType  = $r->fetch_assoc();
    $accountType  = array($accountType['accountType']);
    $sql          = "SELECT * FROM $table1name WHERE userId = '$userId';";
    $database_out = $conn->query($sql);
    $ids          = array();
    $names        = array();
    $emails       = array();
    $genders      = array();
    $goals        = array();
    $campaignids  = array();
    $campaigntime = array();
    $formFlag     = array();
    $mealEng      = array();
    $nutritionEng = array();
    $campaignidAssigned = array();
    $clientIdx = 0;
    while($database_row = $database_out->fetch_assoc()) {
        // filling out info briefs
        array_push($names, $database_row['name']);
        array_push($ids, $database_row['clientId']);
        if($database_row['cEmail'] != ''){
            array_push($emails, $database_row['cEmail']);  
        }      
        array_push($formFlag, $database_row['completed']);
        array_push($mealEng, $database_row['mealEng']);
        array_push($nutritionEng, $database_row['nutritionEng']);
        // --------------- update gender and goal based on possible client's reply
        $tempClientId   = $database_row['clientId'];
        $sqlupdate      = "SELECT qKey, qAnswer, optionsText FROM $table2name WHERE userId = '$userId' AND clientId = '$tempClientId';";
        $dOut           = $conn->query($sqlupdate);
        while($dOut_row = $dOut->fetch_assoc()) {
            if($dOut_row['qKey'] == 'gender'){
                $genderUpdateIndex = $dOut_row['qAnswer'];
                $genderText = $dOut_row['optionsText'];
                $genderText = explode(",", $genderText);
                if($genderUpdateIndex != ''){
                    $updatedGender = $genderText[$genderUpdateIndex];
                }
            } elseif($dOut_row['qKey'] == 'goal') {
                $goalUpdateIndex = $dOut_row['qAnswer'];
                $goalText = $dOut_row['optionsText'];
                $goalText = explode(",", $goalText);
                if($goalUpdateIndex != ''){
                    $updatedGoal = $goalText[$goalUpdateIndex];
                }
            } elseif($dOut_row['qKey'] == 'email') {
                $emailText = $dOut_row['qAnswer'];
                $updatedEmail = $emailText;
            }
        }
        if(isset($updatedGender) && isset($updatedGoal)) {
            array_push($genders, $updatedGender);
            array_push($goals, $updatedGoal);
            unset($updatedGoal);
            unset($updatedGender);
        } else {
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
        }
        if(isset($updatedEmail)){
            if($updatedEmail != '') {
                if($updatedEmail != $database_row['cEmail']){
                    // replace the entry
                    $emails[$clientIdx] = $updatedEmail;
                    unset($updatedEmail);
                } 
                elseif($updatedEmail == $database_row['cEmail']){
                }
            } elseif($updatedEmail == '') {
                if($updatedEmail != $database_row['cEmail']){
                }   
                elseif($updatedEmail == $database_row['cEmail']){
                    array_push($emails, 'No response');
                }
            }
        } else {
            if($database_row['cEmail'] == ''){
                array_push($emails, 'No response');
            }           
        }
        array_push($campaignids, $database_row['campaignIdSource']);
        array_push($campaigntime, $database_row['campaignTimeStamp']);
        array_push($campaignidAssigned, $database_row['campaignId']);
        $clientIdx++;
    }
    
    $userInfo['names']          = $names;
    $userInfo['ids']            = $ids;
    $userInfo['emails']         = $emails;
    $userInfo['genders']        = $genders;
    $userInfo['goals']          = $goals;
    $userInfo['campaignids']    = $campaignids;
    $userInfo['campaigntime']   = $campaigntime; 
    $userInfo['campaignidAssigned'] = $campaignidAssigned;        
    $userInfo['formWasCreated'] = $formFlag;
    $userInfo['mealEng']        = $mealEng;
    $userInfo['nutritionEng']   = $nutritionEng;
    $userInfo['accountType']    = $accountType;

    return $userInfo;
}


function extractClientInfo($clientId, $userId, $nutritionEng, $mealEng, $clientIdVec) {
    
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
    // find index
    $idx = array_search($clientId, $clientIdVec);
    $mealEng = $mealEng[$idx];
    $nutritionEng = $nutritionEng[$idx];
    
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
        
        $out = '{"qKey":         ["' . $qKey          . '"],'.
               ' "qIdx":         "'  . $qIdx          . '",' .
               ' "userId":       "'  . $userId        . '",' .
               ' "clientId":     "'  . $clientId      . '",' .
               ' "mealEng":      "'  . $mealEng       . '",' .
               ' "nutritionEng": "'  . $nutritionEng  . '",' .
               ' "qAnswer":      "'  . $qAnswer       . '",' .
               ' "optionsText":  ['  . $temp          . ']}';

        $clientInfo[$kk] =  json_decode($out);
        $kk++;
    }
    
    return $clientInfo;
}




/// -------------------------
/// main routin starts here.
/// -------------------------
$userdata          = json_decode($_POST['userInfo']);
$userInfo          = extractUserInfo($userdata->userId);
if($userdata->clientId != '') {
    $clientInfo    = extractClientInfo($userdata->clientId, $userdata->userId, 
                                        $userInfo['nutritionEng'], 
                                        $userInfo['mealEng'], 
                                        $userInfo['ids']);
                                        
    $user_bmi      = calculateBmi($clientInfo);
    $user_bmr      = calculateBmr($clientInfo);
    $user_if       = calculateIf($clientInfo);
    $user_macro    = calculateMacro($clientInfo);
    $user_micro    = calculateMicro($clientInfo);
    $user_cal      = calculateCalories($clientInfo);    
    $user_meal     = calculateMeal($clientInfo);
    $output        = dataPrep($user_bmi, $user_bmr, $user_if, $user_macro, $user_micro, $user_cal, $user_meal);

} else {
    $output = '';
    $clientInfo = '';
}
$userInfo['client'] = $output;
$userInfo['input']  = $clientInfo;
echo json_encode($userInfo);


?>



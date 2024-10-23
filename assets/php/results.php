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
    $table4name   = "campaignAlloc";
    // Create connection
    $conn         = new mysqli($servername, $loginname, $password, $dbname);
    // get the campaign source IDs
    $sql          = "SELECT campaignIdSource, completed, campaignTimeStamp FROM $table4name WHERE userId = '$userId';";
    $r            = $conn->query($sql);
    $campaignids  = array();
    $formFlag     = array();
    $campaigntime = array();
    while($c = $r->fetch_assoc()) {
        array_push($campaignids, $c['campaignIdSource']);
        array_push($campaigntime, $c['campaignTimeStamp']);
        array_push($formFlag, $c['completed']);
    }

    // check if the client exists.
    $sql          = "SELECT accountType FROM $table3name WHERE userId = '$userId';";
    $r            = $conn->query($sql);
    $accountType  = $r->fetch_assoc();
    $accountType  = array($accountType['accountType']);
    $sql          = "SELECT * FROM $table1name WHERE userId = '$userId';";
    $database_out = $conn->query($sql);
    $ids          = array();
    $names        = array();
    $emails       = array();
    $genders      = array();
    $goals        = array();
    $mealEng      = array();
    $nutritionEng = array();
    $campaignidAssigned = array();
    $clientHasResponded = array();
    $clientIdx = 0;
    while($database_row = $database_out->fetch_assoc()) { 
        // filling out info briefs
        array_push($names, $database_row['name']);
        array_push($ids, $database_row['clientId']);
        if($database_row['cEmail'] != ''){
            array_push($emails, $database_row['cEmail']);  
        }      
        array_push($mealEng, $database_row['mealEng']);
        array_push($nutritionEng, $database_row['nutritionEng']);
        // --------------- update gender and goal based on possible client's reply
        $tempClientId   = $database_row['clientId'];
        $sqlupdate      = "SELECT qKey, qAnswer, optionsText FROM $table2name WHERE userId = '$userId' AND clientId = '$tempClientId';";
        $dOut           = $conn->query($sqlupdate);
        $numResponses   = 0;
        while($dOut_row = $dOut->fetch_assoc()) {
            // determine if the client has responded
            if($dOut_row['qAnswer'] != ''){
                $numResponses++;
            }
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
                array_push($genders, '');
            }
            if($database_row['goal'] == 0) {
                array_push($goals, 'increase testosterone');
            } elseif($database_row['goal'] == 1) {
                array_push($goals, 'gain muscle');
            } elseif($database_row['goal'] == 2) {
                array_push($goals, 'lose weight');
            } else {
                array_push($goals, '');
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
                    array_push($emails, '');
                }
            }
        } else {
            if($database_row['cEmail'] == ''){
                array_push($emails, '');
            }           
        }
        array_push($campaignidAssigned, $database_row['campaignId']);
        array_push($clientHasResponded, $numResponses);
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
    $userInfo['clientHasResponded'] = $clientHasResponded;

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

function deleteClient($userid, $clientid) {
    $servername   = "127.0.0.1";
    $loginname    = "root";
    $password     = "@Ssia123";
    $dbname       = "Users";
    $table1name   = "Users";
    $table2name   = "userAllocation";
    
    // Create connection
    $conn         = new mysqli($servername, $loginname, $password, $dbname);
    // delete entry from Users table
    $sql          = "DELETE FROM $table1name WHERE userId = '$userid' AND  clientId = '$clientid';";
    $conn->query($sql);
    // delete entry from userAllocation table
    $sql          = "DELETE FROM $table2name WHERE userId = '$userid' AND  clientId = '$clientid';";
    $conn->query($sql);
    // add the entry back into the userAllocation with empty entries
    $telegramNewChat = 0;
    $sql          = "INSERT INTO $table2name (userId, clientId, campaignId, name, cEmail, gender, phoneNumber, telegramChatId, telegramUserName, telegramNewChat, goal, nutritionEng, mealEng, descAddress) VALUES('$userid','$clientid','', '', '', '', '', '', '', '$telegramNewChat', '', '', '', '');";
    $conn->query($sql);
    $conn->close();
    // cleanup the related files
    // Specify the directory to search
    $directory = '../../userPages/';

    // Specify the file pattern to search for (e.g., '*.txt')
    $filePattern = $userid . $clientid . '*.html';

    // Use glob to find the files matching the pattern
    $files = glob($directory . '/' . $filePattern);

    if (!empty($files)) {
        foreach ($files as $file) {
            // Attempt to delete the file
            unlink($file);
        }
    }
}



/// -------------------------
/// main routin starts here.
/// -------------------------
$userdata          = json_decode($_POST['userInfo']);
if(isset($userdata->flag) && $userdata->flag == 'deleteClient'){
    deleteClient($userdata->userId, $userdata->clientId);
}
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



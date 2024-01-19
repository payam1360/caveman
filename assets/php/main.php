<?php

require_once "functions.php";

function saveUserDataIntoDB($Questions, $userId, $clientId, $campaignId, $ip) {
       
    $servername  = "127.0.0.1";
    $loginname   = "root";
    $password    = "@Ssia123";
    $dbname      = "Users";
    $table1name  = "Users";
    $table2name  = "userAllocation";
    // Create connection
    $conn        = new mysqli($servername, $loginname, $password, $dbname);
    // check if the client exists.
    $sql         = "SELECT * FROM $table1name WHERE userId = '$userId' AND clientId = '$clientId' AND campaignId = '$campaignId';";
    $db_out      = $conn->query($sql);
    $gender      = [];
    $goal        = [];
    // if request from client page -> entries already exists in the db: update qAnswer only
    // if request from public page -> entries do not exist for new clients: insert new entries
    // if request from questions design page -> entries do not exist: insert new entries
    if($db_out->num_rows != 0) { // request from clinet page -> need to update
        $kk = 0;
        while($row = $db_out->fetch_assoc() && $Questions[$kk]->qType[0] != 'message'){
            $options = "";
            $optionsText = "";
            if($Questions[$kk]->options[0] == ""){
            } else {
                $options = implode(",", $Questions[$kk]->options[0]);
            }
            if($Questions[$kk]->qType[0] == "button"){
                $optionsText = implode(",", $Questions[$kk]->optionsText[0]);
            }
            $qIdx = $Questions[$kk]->qIdx;
            $qType = $Questions[$kk]->qType[0];
            $qContent = $Questions[$kk]->qContent[0];
            $qAnswer = $Questions[$kk]->qAnswer;
            $visited = $Questions[$kk]->visited;
            $qRequired = $Questions[$kk]->qRequired;
            $qKey = $Questions[$kk]->qKey[0];
            if($qKey == 'gender') {
                $gender = $qAnswer;
            } elseif($qKey == 'goal') {
                $goal = $qAnswer;
            } 
            $campaignTime = date("Y-m-d");
            $sql = "UPDATE $table1name SET 
                                            campaignTime = '$campaignTime', 
                                            qIdx = '$qIdx', 
                                            qType = '$qType', 
                                            qContent = '$qContent', 
                                            qAnswer = '$qAnswer', 
                                            options = '$options', 
                                            optionsText = '$optionsText', 
                                            visited = '$visited', 
                                            qRequired = '$qRequired', 
                                            qKey = '$qKey' 
                                            WHERE 
                                            userId = '$userId' 
                                            AND 
                                            clientId = '$clientId' 
                                            AND 
                                            campaignId = '$campaignId' 
                                            AND 
                                            qIdx = '$qIdx';";
            $conn->query($sql);
            $kk++;
        }
        // Also, update userAllocation table for gender and goal based on new clients response.
        // not the initial Add client info added by the user
        if($gender != [] || $goal != []) {
            $sql = "UPDATE $table2name SET 
                                            gender = '$gender', 
                                            goal   = '$goal'
                                            WHERE 
                                            userId = '$userId' 
                                            AND 
                                            clientId = '$clientId' 
                                            AND 
                                            campaignId = '$campaignId';";
            $db_out      = $conn->query($sql);
        }
    } else { // this is coming from public or question page
        $kk = 0;
        while(!is_null($Questions[$kk]->qIdx) && $Questions[$kk]->qIdx >= 0 && $Questions[$kk]->qType[0] != 'message'){
            $options = "";
            $optionsText = "";
            if($Questions[$kk]->options[0] == ""){
            } else {
                $options = implode(",", $Questions[$kk]->options[0]);
            }
            if($Questions[$kk]->qType[0] == "button"){
                $optionsText = implode(",", $Questions[$kk]->optionsText[0]);
            }
            $qIdx = $Questions[$kk]->qIdx;
            $qType = $Questions[$kk]->qType[0];
            $qContent = $Questions[$kk]->qContent[0];
            $qAnswer = $Questions[$kk]->qAnswer;
            $visited = $Questions[$kk]->visited;
            $qRequired = $Questions[$kk]->qRequired;
            $campaignTime = date("Y-m-d");
            $qKey = $Questions[$kk]->qKey[0];
            $sql = "INSERT INTO $table1name (userId, clientId, ip, campaignId, campaignTime, qIdx, qType, qContent, qAnswer, options, optionsText, visited, qRequired, qKey) VALUES('$userId', '$clientId', '$ip', '$campaignId', '$campaignTime', '$qIdx', '$qType', '$qContent', '$qAnswer', '$options', '$optionsText', '$visited', '$qRequired', '$qKey');";
            $conn->query($sql);
            $kk++;
        }
    }
    $conn->close();
    return true;
}

function extractUserInfo($info, $ip) {
    if($info == 'main') {

        $userId      = '0';
        $clientId    = '0';
        $campaignId  = '0';
        $userInfo['userId']       = $userId;
        $userInfo['clientId']     = $clientId;
        $userInfo['campaignId']   = $campaignId;
        $userInfo['mealEng']      = 0; // 0 means AI takes over, 1 means nutritionist can modify pre-saved info in DB
        $userInfo['nutritionEng'] = 0; // 0 means AI takes over, 1 means nutritionist can modify pre-saved info in DB
        
    } else {

        $userId     = substr($info, 0, 6);
        $clientId   = substr($info, 6, 5);
        $campaignId = substr($info, 11, 7);
        $userInfo['userId']       = $userId;
        $userInfo['clientId']     = $clientId;
        $userInfo['campaignId']   = $campaignId;
        $userInfo['mealEng']      = 0; // 0 means AI takes over, 1 means nutritionist can modify pre-saved info in DB
        $userInfo['nutritionEng'] = 0; // 0 means AI takes over, 1 means nutritionist can modify pre-saved info in DB
    }
    return $userInfo;
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
$userInfo      = extractUserInfo($userdata->IdInfo, $ip);
$data          = $userdata->data;
$data['userId']        = $userInfo['userId'];
$data['clientId']      = $userInfo['clientId'];
$data['campaignId']    = $userInfo['campaignId'];
$data[0]->mealEng      = $userInfo['mealEng'];
$data[0]->nutritionEng = $userInfo['nutritionEng'];
$dbflag        = saveUserDataIntoDB($data, $data['userId'], $data['clientId'], $data['campaignId'], $ip);
// perform calculations
$user_bmi      = calculateBmi($data);
$user_bmr      = calculateBmr($data);
$user_if       = calculateIf($data);
$user_macro    = calculateMacro($data);
$user_micro    = calculateMicro($data);
$output        = dataPrep($user_bmi, $user_bmr, $user_if, $user_macro, $user_micro);
echo json_encode($output);
?>



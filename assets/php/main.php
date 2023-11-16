<?php

function saveUserDataIntoDB($Questions, $userId, $clientId, $campaignId, $ip) {
       
    $servername  = "127.0.0.1";
    $loginname   = "root";
    $password    = "@Ssia123";
    $dbname      = "Users";
    $table1name  = "Users";
    // Create connection
    $conn        = new mysqli($servername, $loginname, $password, $dbname);
    // check if the client exists.
    $sql         = "SELECT * FROM $table1name WHERE userId = '$userId' AND clientId = '$clientId' AND campaignId = '$campaignId';";
    $db_out      = $conn->query($sql);
    // if request from client page -> entries already exists in the db: update qAnswer only
    // if request from public page -> entries do not exist for new clients: insert new entries
    // if request from questions design page -> entries do not exist: insert new entries
    $kk = 0;
    if($db_out->num_rows != 0) { // request from clinet page -> need to update
        while($row = $db_out->fetch_assoc() && $Questions[$kk]->qType[0] != 'message'){
            
            $options = "";
            $optionsText = "";
            if($Questions[$kk]->options[0] == ""){
            } else {
                for($kx = 0; $kx < count($Questions[$kk]->options[0]); $kx++){
                    $options = $options . "," . $Questions[$kk]->options[0][$kx];
                }
            }
            if($Questions[$kk]->qType[0] == "button"){
                for($kx = 0; $kx < count($Questions[$kk]->optionsText[0]); $kx++){
                    $optionsText = $optionsText . "," . $Questions[$kk]->optionsText[0][$kx];
                }
            }
            $qIdx = $Questions[$kk]->qIdx;
            $qType = $Questions[$kk]->qType[0];
            $qContent = $Questions[$kk]->qContent[0];
            $qAnswer = $Questions[$kk]->qAnswer;
            $visited = $Questions[$kk]->visited;
            $qRequired = $Questions[$kk]->qRequired;
            $qKey = $Questions[$kk]->qKey[0];
            $campaignTime = date("Y-m-d");
            $sql = "UPDATE $table1name SET campaignTime = '$campaignTime', qIdx = '$qIdx', qType = '$qType', qContent = '$qContent', qAnswer = '$qAnswer', options = '$options', optionsText = '$optionsText', visited = '$visited', qRequired = '$qRequired', qKey = '$qKey' WHERE userId = '$userId' AND clientId = '$clientId' AND campaignId = '$campaignId' AND qIdx = '$qIdx';";
            $conn->query($sql);
            $kk++;
        }
    } else { // this is coming from public or question page
        $kk = 0;
        while(!is_null($Questions[$kk]->qIdx) && $Questions[$kk]->qIdx >= 0 && $Questions[$kk]->qType[0] != 'message'){
            $kk = $Questions[$kk]->qIdx;
            $options = "";
            $optionsText = "";
        
            if($Questions[$kk]->options[0] == ""){
            } else {
                for($kx = 0; $kx < count($Questions[$kk]->options[0]); $kx++){
                    $options = $options . "," . $Questions[$kk]->options[0][$kx];
                }
            }
            if($Questions[$kk]->qType[0] == "button"){
                for($kx = 0; $kx < count($Questions[$kk]->optionsText[0]); $kx++){
                    $optionsText = $optionsText . "," . $Questions[$kk]->optionsText[0][$kx];
                }
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


function calculateBmi($data){
    $kk = 0;
    $weightDone = false;
    $heightDone = false;
    while(isset($data[$kk]->qIdx)){
        if($data[$kk]->qKey[0] == 'weight' && $weightDone == false){
            $Userweight = $data[$kk]->qAnswer;
            if(str_contains($Userweight, '<')) {
                $Userweight = 70; // minimum weigh
            } elseif(str_contains($Userweight, '>')){
                $Userweight = 300; // maximum weight
            } else {
                $Userweight = $Userweight;
            }
            $weightDone = true;
        } else { // weight is required
        }
        if($data[$kk]->qKey[0] == 'height' && $heightDone == false){
            $Userheight = $data[$kk]->qAnswer;
            if(str_contains($Userheight, '<')){
                $Userheight = 4 * 12; // minimum heigh
            } elseif(str_contains($Userheight, '>')){
                $Userheight = 7 * 12; // maximum height
            } else {
                $height = explode('-', $Userheight);
                $Userheight = intval($height[0]) * 12 + intval($height[1]);
            }
            $heightDone = true;
        } else {
        }
        if($weightDone == true && $heightDone == true){
            break;
        }
        $kk++;
    }
    $BMI = $Userweight * 703 / pow($Userheight, 2);
    return($BMI);
}

function calculateBmr($data) {
    $kk = 0;
    $weightDone = false;
    $heightDone = false;
    $ageDone    = false;
    $genderDone = false;
    $stressDone = false;
    
    while(isset($data[$kk]->qIdx)){
        if($data[$kk]->qKey[0] == 'weight' && $weightDone == false){
            $Userweight = $data[$kk]->qAnswer;
            if(str_contains($Userweight, '<')) {
                $Userweight = 70; // minimum weigh
            } elseif(str_contains($Userweight, '>')){
                $Userweight = 300; // maximum weight
            } else {
                $Userweight = $Userweight;
            }
            $weightDone = true;
        } else { // weight is required
        }
        if($data[$kk]->qKey[0] == 'height' && $heightDone == false){
            $Userheight = $data[$kk]->qAnswer;
            if(str_contains($Userheight, '<')){
                $Userheight = 4 * 12; // minimum heigh
            } elseif(str_contains($Userheight, '>')){
                $Userheight = 7 * 12; // maximum height
            } else {
                $height = explode('-', $Userheight);
                $Userheight = intval($height[0]) * 12 + intval($height[1]);
            }
            $heightDone = true;
        } else {
            // height is required.
        }
        if($data[$kk]->qKey[0] == 'age' && $ageDone == false){
            $Userage = $data[$kk]->qAnswer;
            if(str_contains($Userage, '<')) {
                $Userage = 18; // minimum age
            } elseif(str_contains($Userage, '>')){
                $Userage = 90; // maximum age
            } else {
                $Userage = $Userage;
            }
            $ageDone = true;
        } else {
            // age is needed
        }
        if($data[$kk]->qKey[0] == 'gender' && $genderDone == false){
            $Usergender = $data[$kk]->optionsText[0][$data[$kk]->qAnswer];
            $genderDone = true;
        } else {
            $Usergender = 'Male'; // by default
        }
        
        if($data[$kk]->qKey[0] == 'stress' && $stressDone == false){
            $Userstress = $data[$kk]->optionsText[0][$data[$kk]->qAnswer];
            $stressDone = true;
        } else {
            $Userstress = 'relaxed'; // by default
        }
        
        if($weightDone == true && $heightDone == true && $ageDone == true && $genderDone == true && $stressDone == true){
            break;
        }
        $kk++;
    }
    if($Userstress == 'relaxed') {
        $stressFactor = 1.2;
    } elseif($Userstress == 'manageable') {
        $stressFactor = 1.65;
    } else {
        $stressFactor = 2.25;
    }
    
    if($Usergender == 'Male') {
        $BMR = $stressFactor * ( 66.47 + (6.24 * intval($Userweight)) + (12.7 * intval($Userheight)) - (6.75 * intval($Userage)));
    } else {
        $BMR = $stressFactor * ( 65.51 + (4.35 * intval($Userweight)) + (4.7 * intval($Userheight)) - (4.7 * intval($Userage)));
    }
    return($BMR);
}

function calculateIf($data){
    // this function is the algorithm for calculating intervals in which the user is 
    // recommended for fasting. Fasting increase IGF or growth hormons.  
    // calculate intermittent fasting interval
    $kk = 0;
    $weightDone = false;
    $ageDone    = false;
    $genderDone = false;
    $goalDone   = false;
    $ifDone     = false;
    $IF         = [[24,0],[24,0],[24,0],[24,0],[24,0],[24,0],[24,0]];
    while(isset($data[$kk]->qIdx)){
        if($data[$kk]->qKey[0] == 'weight' && $weightDone == false){
            $Userweight = $data[$kk]->qAnswer;
            if(str_contains($Userweight, '<')) {
                $Userweight = 70; // minimum weigh
            } elseif(str_contains($Userweight, '>')){
                $Userweight = 300; // maximum weight
            } else {
                $Userweight = $Userweight;
            }
            $weightDone = true;
        } else {
            // weight is required
        }
        if($data[$kk]->qKey[0] == 'age' && $ageDone == false){
            $Userage = $data[$kk]->qAnswer;
            if(str_contains($Userage, '<')) {
                $Userage = 18; // minimum age
            } elseif(str_contains($Userage, '>')){
                $Userage = 90; // maximum age
            } else {
                $Userage = $Userage;
            }
            $ageDone = true;
        } else {
            // age is needed
        }
        if($data[$kk]->qKey[0] == 'gender' && $genderDone == false){
            $Usergender = $data[$kk]->optionsText[0][$data[$kk]->qAnswer];
            $genderDone = true;
        } else {
            $Usergender = 'Male'; // by default
        }
        if($data[$kk]->qKey[0] == 'goal' && $goalDone == false){
            $Usergoal = $data[$kk]->optionsText[0][$data[$kk]->qAnswer];
            $goalDone = true;
        } else {
            $Usergoal = 'Lose'; // by default
        }
        $kk++;
    }
    // IF suggestion based on user's spec
    // -----------------------------------------------------------
    
    if(!$ifDone == false && $Userweight > 70 && $Userweight < 120) {
        if($Usergender == 'Male'){
            if($Usergoal == 'Lose') {
                $IF = [[24,0],[24,0],[16,8],[24,0],[24,0],[16,8],[24,0]];
                $ifDone = true;
            } elseif($Usergoal == 'Gain') {
                $IF = [[24,0],[24,0],[24,0],[24,0],[24,0],[16,8],[24,0]];
                $ifDone = true;
            }
        } elseif($Usergender == 'Female'){
            if($Usergoal == 'Lose') {
                $IF = [[16,8],[24,0],[16,8],[24,0],[24,0],[16,8],[24,0]];
                $ifDone = true;
            } elseif($Usergoal == 'Gain') {
                $IF = [[24,0],[24,0],[16,8],[24,0],[24,0],[16,8],[24,0]];
                $ifDone = true;
            }
        }
    }
    if(!$ifDone == false && $Userweight > 120 && $Userweight < 220) {
        if($Usergender == 'Male'){
            if($Usergoal == 'Lose') {
                $IF = [[24,0],[24,0],[16,8],[24,0],[24,0],[16,8],[24,0]];
                $ifDone = true;
            } elseif($Usergoal == 'Gain') {
                $IF = [[24,0],[24,0],[24,0],[24,0],[24,0],[16,8],[24,0]];
                $ifDone = true;
            }
        } elseif($Usergender == 'Female'){
            if($Usergoal == 'Lose') {
                $IF = [[16,8],[24,0],[16,8],[24,0],[24,0],[16,8],[24,0]];
                $ifDone = true;
            } elseif($Usergoal == 'Gain') {
                $IF = [[24,0],[24,0],[16,8],[24,0],[24,0],[16,8],[24,0]];
                $ifDone = true;
            }
        }
    }
    if(!$ifDone == false && $Userweight > 220 && $Userweight < 300) {
        if($Usergender == 'Male'){
            if($Usergoal == 'Lose') {
                $IF = [[24,0],[24,0],[16,8],[24,0],[24,0],[16,8],[24,0]];
                $ifDone = true;
            } elseif($Usergoal == 'Gain') {
                $IF = [[24,0],[24,0],[24,0],[24,0],[24,0],[16,8],[24,0]];
                $ifDone = true;
            }
        } elseif($Usergender == 'Female'){
            if($Usergoal == 'Lose') {
                $IF = [[16,8],[24,0],[16,8],[24,0],[24,0],[16,8],[24,0]];
                $ifDone = true;
            } elseif($Usergoal == 'Gain') {
                $IF = [[24,0],[24,0],[16,8],[24,0],[24,0],[16,8],[24,0]];
                $ifDone = true;
            }
        }
    }

    // -----------------------------------------------------------
    return($IF);
}
function calculateMacro($data){
    // add Macro computation code
    
    return([20, 40, 10, 30]);
}
function calculateMicro($data){
    // add Micro computation code

    return([5,7,8,10,2,4,12,5.2]);
}

function dataPrep($user_bmi, $user_bmr, $user_if, $user_macro, $user_micro){
    $data = array('status' => 0,
                 'bmi'   => $user_bmi,
                 'bmr'   => $user_bmr,
                 'If'    => $user_if,
                 'macro' => $user_macro,
                 'micro' => $user_micro);
    return $data;
}

function extractUserInfo($info, $ip) {
    if(strlen($info) == 1) {
        $servername   = "127.0.0.1";
        $loginname    = "root";
        $password     = "@Ssia123";
        $dbname       = "Users";
        $table1name   = "Users";
        // Create connection
        $conn         = new mysqli($servername, $loginname, $password, $dbname);
        // check if the client exists.
        $sql          = "SELECT clientId, campaignId FROM $table1name WHERE ip = '$ip';";
        $database_out = $conn->query($sql);
        $database_row = $database_out->fetch_assoc();
        if(is_null($database_row)) {
            $userId = '0';
            $clientId = mt_rand(10000, 99999);;
            $campaignId  = substr(md5(rand()), 0, 7);
            $userInfo['userId'] = $userId;
            $userInfo['clientId'] = $clientId;
            $userInfo['campaignId'] = $campaignId;
            
        } else { // visitor not found
            $userId = '0';
            $clientId = $database_row['clientId'];
            $campaignId = $database_row['campaignId'];
            $userInfo['userId'] = $userId;
            $userInfo['clientId'] = $clientId;
            $userInfo['campaignId'] = $campaignId;
        }
    } else {
        $userId     = substr($info, 0, 6);
        $clientId   = substr($info, 6, 5);
        $campaignId = substr($info, 11, 7);
        $userInfo['userId'] = $userId;
        $userInfo['clientId'] = $clientId;
        $userInfo['campaignId'] = $campaignId;
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
$userId        = $userInfo['userId'];
$clientId      = $userInfo['clientId'];
$campaignId    = $userInfo['campaignId'];
$data          = $userdata->data;
$dbflag        = saveUserDataIntoDB($data, $userId, $clientId, $campaignId, $ip);
// perform calculations
$user_bmi      = calculateBmi($data);
$user_bmr      = calculateBmr($data);
$user_if       = calculateIf($data);
$user_macro    = calculateMacro($data);
$user_micro    = calculateMicro($data);
$output        = dataPrep($user_bmi, $user_bmr, $user_if, $user_macro, $user_micro);
echo json_encode($output);


?>



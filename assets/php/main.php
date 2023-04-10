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
    
    $Userweight = 85;
    $Userheight = 1.7;
    return($Userweight / pow($Userheight, 2));
}
function calculateIf($data){
    //preg_match_all('!\d+\.*\d*!', $weight, $matches_weight);
    //preg_match_all('!\d+\.*\d*!', $height, $matches_height);
    //$Userweight = ($matches_weight[0][0] + $matches_weight[0][1]) / 2 * 0.45359237;
    //$Userheight = ($matches_height[0][0] + $matches_height[0][1]) / 2 * 0.3048;
    $Userweight = 85;
    $Userheight = 1.7;
// calculate intermittent fasting interval
    return(16);
}
function calculateMacro($data){
    //preg_match_all('!\d+\.*\d*!', $weight, $matches_weight);
    //preg_match_all('!\d+\.*\d*!', $height, $matches_height);
    //$Userweight = ($matches_weight[0][0] + $matches_weight[0][1]) / 2 * 0.45359237;
    //$Userheight = ($matches_height[0][0] + $matches_height[0][1]) / 2 * 0.3048;
// calculate intermittent fasting interval
    return([20, 40, 10, 30]);
}
function calculateMicro($data){
    //preg_match_all('!\d+\.*\d*!', $weight, $matches_weight);
    //preg_match_all('!\d+\.*\d*!', $height, $matches_height);
    //$Userweight = ($matches_weight[0][0] + $matches_weight[0][1]) / 2 * 0.45359237;
    //$Userheight = ($matches_height[0][0] + $matches_height[0][1]) / 2 * 0.3048;
// calculate intermittent fasting interval
    return([5,7,8,10,2,4,12,5.2]);
}

function dataPrep($user_bmi, $user_if, $user_macro, $user_micro){
    $data = array('status' => 0,
                 'bmi' => $user_bmi,
                 'If'  => $user_if,
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
$user_if       = calculateIf($data);
$user_macro    = calculateMacro($data);
$user_micro    = calculateMicro($data);
$output        = dataPrep($user_bmi, $user_if, $user_macro, $user_micro);
echo json_encode($output);


?>



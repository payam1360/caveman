<?php

define("WEIGHT", 2);
define("HEIGHT", 3);

function saveUserDataIntoDB($Questions, $userId, $clientId, $campaignId, $campaignTime) {
       
    $servername  = "127.0.0.1";
    $loginname   = "root";
    $password    = "@Ssia123";
    $dbname      = "Users";
    $table1name  = "Users";
    // Create connection
    $conn        = new mysqli($servername, $loginname, $password, $dbname);
    // check if the client exists.
    $sql         = "SELECT EXISTS (SELECT clientId FROM " . $table1name . " WHERE clientId = '" . $clientId . "');";
    $ex          = $conn->query($sql);
    if($ex->fetch_column(0) == 0){
        $ex = false;
    } else {
        $ex = true;
    }
    $kk          = 0;
    while($Questions[$kk]->qIdx >= 0 && $Questions[$kk]->qType[0] != 'message'){
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
        
        if($ex) { // update the already exists entry
            $sql = "UPDATE $table1name SET userId =  '$userId', clientId = '$clientId', campaignId = '$campaignId', campaignTime = '$campaignTime', qIdx = '$qIdx', qType = '$qType', qContent = '$qContent', qAnswer = '$qAnswer', options = '$options', optionsText = '$optionsText', visited = '$visited', qRequired = '$qRequired' WHERE qIdx = '$qIdx';";
        } else {
            $sql = "INSERT INTO " . $table1name . " (userId, clientId, campaignId, campaignTime, qIdx, qType, qContent, qAnswer, options, optionsText, visited, qRequired) VALUES('" . $userId . "','" . $clientId . "','" . $campaignId . "','" . $campaignTime . "','" . $Questions[$kk]->qIdx . "','" . $Questions[$kk]->qType[0] . "','" . $Questions[$kk]->qContent[0] . "','" . $Questions[$kk]->qAnswer . "','" . $options . "','" . $optionsText . "','" . $Questions[$kk]->visited . "','" . $Questions[$kk]->qRequired . "')";
        }
        $conn->query($sql);
        $kk++;
    }
    $conn->close();
    return true;
}

function calculateBmi($weight, $height){
    preg_match_all('!\d+\.*\d*!', $weight, $matches_weight);
    preg_match_all('!\d+\.*\d*!', $height, $matches_height);
    $Userweight = ($matches_weight[0][0] + $matches_weight[0][1]) / 2 * 0.45359237;
    $Userheight = ($matches_height[0][0] + $matches_height[0][1]) / 2 * 0.3048;
    return($Userweight / pow($Userheight, 2));
}
function calculateIf($weight, $height){
    preg_match_all('!\d+\.*\d*!', $weight, $matches_weight);
    preg_match_all('!\d+\.*\d*!', $height, $matches_height);
    $Userweight = ($matches_weight[0][0] + $matches_weight[0][1]) / 2 * 0.45359237;
    $Userheight = ($matches_height[0][0] + $matches_height[0][1]) / 2 * 0.3048;
// calculate intermittent fasting interval
    return(16);
}
function calculateMacro($weight, $height){
    preg_match_all('!\d+\.*\d*!', $weight, $matches_weight);
    preg_match_all('!\d+\.*\d*!', $height, $matches_height);
    $Userweight = ($matches_weight[0][0] + $matches_weight[0][1]) / 2 * 0.45359237;
    $Userheight = ($matches_height[0][0] + $matches_height[0][1]) / 2 * 0.3048;
// calculate intermittent fasting interval
    return([20, 40, 10, 30]);
}
function calculateMicro($weight, $height){
    preg_match_all('!\d+\.*\d*!', $weight, $matches_weight);
    preg_match_all('!\d+\.*\d*!', $height, $matches_height);
    $Userweight = ($matches_weight[0][0] + $matches_weight[0][1]) / 2 * 0.45359237;
    $Userheight = ($matches_height[0][0] + $matches_height[0][1]) / 2 * 0.3048;
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

/// -------------------------
/// main routin starts here.
/// -------------------------
$userdata      = json_decode($_POST['userInfo']);
$cookie_name   = 'nutrition4guys';
if(!isset($_COOKIE[$cookie_name])) {
    $clientId = mt_rand(10000, 99999);
    $userId = 0;
    $campaignId = 0;
    $campaignTime = date('Y-m-d');
    $cookie_value = 'UI' . $userId . '_' . 'CI' . $clientId;
    $expire = time() + (86400 * 30);
    $arr_cookie_options = array (
                    'expires' => $expire,
                    'path' => '/',
                    'secure' => true,
                    'httponly' => true,
                    'samesite' => 'None'
                    );
    setcookie($cookie_name, $cookie_value, $arr_cookie_options);
} else {
    $cookie = $_COOKIE[$cookie_name];
    $idx    = strpos($cookie, '_');
    $userId = substr($cookie, 2, $idx - 2);
    $clientId = substr($cookie, $idx+3, 5);
    $campaignId = 0;
    $campaignTime = date('Y-m-d');
}
$dbflag        = saveUserDataIntoDB($userdata, $userId, $clientId, $campaignId, $campaignTime);
$user_bmi      = calculateBmi($userdata[WEIGHT]->qAnswer, $userdata[HEIGHT]->qAnswer);
$user_if       = calculateIf($userdata[WEIGHT]->qAnswer, $userdata[HEIGHT]->qAnswer);
$user_macro    = calculateMacro($userdata[WEIGHT]->qAnswer, $userdata[HEIGHT]->qAnswer);
$user_micro    = calculateMicro($userdata[WEIGHT]->qAnswer, $userdata[HEIGHT]->qAnswer);
$data          = dataPrep($user_bmi, $user_if, $user_macro, $user_micro);
echo json_encode($data);


?>



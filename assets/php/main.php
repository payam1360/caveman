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

function kg2lb($kg) {
    return($kg * 0.45);
}
function lb2kg($lb) {
    return($lb * 2.2);
}
function in2cm($in) {
    return($in * 2.54);
}
function cm2in($cm) {
    return($cm * 0.39);
}
function in2ft($in) {
    return($in * 0.083);
}
function ft2in($ft) {
    return($ft * 12);
}

// returns weight in lb
function getWeight($data) {
    $kk = 0;
    $weightDone = false;
    while(isset($data[$kk]->qIdx)){
        if($data[$kk]->qKey[0] == 'weight' && $weightDone == false){
            $Userweight = $data[$kk]->qAnswer;
            if(str_contains($Userweight, '<')) {
                $Userweight = 70; // minimum weigh in lb
            } elseif(str_contains($Userweight, '>')){
                $Userweight = 300; // maximum weight in lb
            } else {
                $Userweight = intval($Userweight);
            }
            $weightDone = true;
        } else { // weight is required
        }
        if($weightDone == true){
            break;
        }
        $kk++;
    }
    return($Userweight);
}

// returns height in inches
function getHeight($data) {
    $kk = 0;
    $heightDone = false;
    while(isset($data[$kk]->qIdx)){
        if($data[$kk]->qKey[0] == 'height' && $heightDone == false){
            $Userheight = $data[$kk]->qAnswer;
            if(str_contains($Userheight, '<')){
                $Userheight = ft2in(4); // minimum heigh in inches
            } elseif(str_contains($Userheight, '>')){
                $Userheight = ft2in(7); // maximum height in inches
            } else {
                $height = explode('-', $Userheight);
                $Userheight = ft2in(intval($height[0])) + intval($height[1]); // hright in inches
            }
            $heightDone = true;
        } else {
        }
        if($heightDone == true){
            break;
        }
        $kk++;
    }
    return($Userheight);
}

// returns age rounded by year
function getAge($data) {
    $ageDone    = false;
    $kk         = 0;
    while(isset($data[$kk]->qIdx)){
        if($data[$kk]->qKey[0] == 'age' && $ageDone == false){
            $Userage = $data[$kk]->qAnswer;
            if(str_contains($Userage, '<')) {
                $Userage = 18; // minimum age
            } elseif(str_contains($Userage, '>')){
                $Userage = 90; // maximum age
            } else {
                $Userage = intval($Userage);
            }
            $ageDone = true;
        } else {
            // age is needed
        }
        if($ageDone == true){
            break;
        }
        $kk++;
    }
    return($Userage);
}

// returns user gender
function getGender($data) {
    $genderDone    = false;
    $kk            = 0;
    while(isset($data[$kk]->qIdx)){
        if($data[$kk]->qKey[0] == 'gender' && $genderDone == false){
            $UsergenderChoice = $data[$kk]->optionsText[0][$data[$kk]->qAnswer];
            if($UsergenderChoice == 'Male') {
                $Usergender = 'Male';
            } else {
                $Usergender = 'Female';
            }
            $genderDone = true;
        } else {
            $Usergender = 'Male'; // by default
        }
        if($genderDone == true){
            break;
        }
        $kk++;
    }
    return($Usergender);
}
// user's stress 
function getStress($data) {
    $stressDone    = false;
    $kk            = 0;
    while(isset($data[$kk]->qIdx)){
        if($data[$kk]->qKey[0] == 'stress' && $stressDone == false){
            $UserstressChoice = $data[$kk]->optionsText[0][$data[$kk]->qAnswer];
            if($UserstressChoice == 'high') {
                $Userstress = 'High';
            } elseif($UserstressChoice == 'manageable') {
                $Userstress = 'Medium';
            } else {
                $Userstress = 'Low';
            }
            $stressDone = true;
        } else {
            $Userstress = 'Low'; // by default
        }
        if($stressDone == true){
            break;
        }
        $kk++;
    }
    return($Userstress);
}
// user's goal
function getGoal($data) {
    $goalDone    = false;
    $kk          = 0;
    while(isset($data[$kk]->qIdx)){
        if($data[$kk]->qKey[0] == 'goal' && $goalDone == false){
            $UsergoalChoice = $data[$kk]->optionsText[0][$data[$kk]->qAnswer];
            
            if($UsergoalChoice == 'lose weight') {
                $Usergoal = 'Lose';
            } elseif($UsergoalChoice == 'gain muscles') {
                $Usergoal = 'Gain';
            } else {
                $Usergoal = 'LessTired';
            }
            $goalDone = true;
        } else {
            $Usergoal = 'Lose'; // by default
        }
        if($goalDone == true){
            break;
        }
        $kk++;
    }
    return($Usergoal);
}

function calculateBmi($data){
    $Userweight = getWeight($data);
    $Userheight = getHeight($data);
    $BMI = lb2kg($Userweight) / pow(in2cm($Userheight)/100, 2);
    return($BMI);
}


// this function is using Harris-Benedict equation for BMR calculation
function calculateBmr($data) {
    
    // convert measurements to standard
    $Userweight = lb2kg(getWeight($data)); 
    $Userheight = in2cm(getHeight($data));
    $Userage    = getAge($data);
    $Usergender = getGender($data);
    $Userstress = getStress($data);
        
    if($Userstress == 'Low') {
        $stressFactor = 1.2;
    } elseif($Userstress == 'Medium') {
        $stressFactor = 1.65;
    } else {
        $stressFactor = 2.25;
    }
    if($Usergender == 'Male') {
        $BMR = $stressFactor * ( 66.47 + (13.75 * $Userweight) + (5.003 * $Userheight) - (6.75 * $Userage));
    } else {
        $BMR = $stressFactor * ( 655.1 + (9.56 * $Userweight) + (1.85 * $Userheight) - (4.7 * $Userage));
    }
    return($BMR);
}

function calculateIf($data){
    // this function is the algorithm for calculating intervals in which the user is 
    // recommended for fasting. Fasting increase IGF or growth hormons.  
    // calculate intermittent fasting interval
    $IF         = [[24,0],[24,0],[24,0],[24,0],[24,0],[24,0],[24,0]];
    $Userweight = getWeight($data);
    $Userage    = getAge($data);
    $Usergender = getGender($data);
    $Usergoal   = getGoal($data);

    // IF suggestion based on user's spec
    // -----------------------------------------------------------
    
    if($Userweight >= 70 && $Userweight < 120) {
        if($Usergender == 'Male'){
            if($Usergoal == 'Lose') {
                $IF = [[24,0],[24,0],[16,8],[24,0],[24,0],[16,8],[24,0]];
            } elseif($Usergoal == 'Gain') {
                $IF = [[24,0],[24,0],[24,0],[24,0],[24,0],[16,8],[24,0]];
            }
        } elseif($Usergender == 'Female'){
            if($Usergoal == 'Lose') {
                $IF = [[16,8],[24,0],[16,8],[24,0],[24,0],[16,8],[24,0]];
            } elseif($Usergoal == 'Gain') {
                $IF = [[24,0],[24,0],[16,8],[24,0],[24,0],[16,8],[24,0]];
            }
        }
    }
    elseif($Userweight >= 120 && $Userweight < 220) {
        if($Usergender == 'Male'){
            if($Usergoal == 'Lose') {
                $IF = [[24,0],[24,0],[16,8],[24,0],[24,0],[16,8],[24,0]];
            } elseif($Usergoal == 'Gain') {
                $IF = [[24,0],[24,0],[24,0],[24,0],[24,0],[16,8],[24,0]];
            }
        } elseif($Usergender == 'Female'){
            if($Usergoal == 'Lose') {
                $IF = [[16,8],[24,0],[16,8],[24,0],[24,0],[16,8],[24,0]];
            } elseif($Usergoal == 'Gain') {
                $IF = [[24,0],[24,0],[16,8],[24,0],[24,0],[16,8],[24,0]];
            }
        }
    }
    elseif($Userweight >= 220 && $Userweight < 300) {
        if($Usergender == 'Male'){
            if($Usergoal == 'Lose') {
                $IF = [[24,0],[24,0],[16,8],[24,0],[24,0],[16,8],[24,0]];
            } elseif($Usergoal == 'Gain') {
                $IF = [[24,0],[24,0],[24,0],[24,0],[24,0],[16,8],[24,0]];
            }
        } elseif($Usergender == 'Female'){
            if($Usergoal == 'Lose') {
                $IF = [[16,8],[24,0],[16,8],[24,0],[24,0],[16,8],[24,0]];
            } elseif($Usergoal == 'Gain') {
                $IF = [[24,0],[24,0],[16,8],[24,0],[24,0],[16,8],[24,0]];
            }
        }
    }
    // -----------------------------------------------------------
    return($IF);
}
// function to calculate adjusted body weight
function calculateAjBW($data) {
    $Userweight = getWeight($data); // lb
    $Userheight = getHeight($data); // inch
    $Usergender = getGender($data);
    // calculation
    if($Usergender == 'Female') {
        //$idealBodyWeight = 49 kg + 1.7 kg per every inch over 5 feet
        $idealBodyWeight = 49 + 1.7 * ($Userheight - 60); // kg
    } else {
        //$idealBodyWeight = 52 kg + 1.9 kg per every inch over 5 feet
        $idealBodyWeight = 52 + 1.9 * ($Userheight - 60); // kg
    }
    $adjustedBodyWeight = $idealBodyWeight + 0.4 * (lb2kg($Userweight) - $idealBodyWeight);
    return(kg2lb($adjustedBodyWeight));
}

function calculateMacro($data){
    // add Macro computation code
    // this function returns Macro requirements to maintain current weight
    // first calculate BMR
    // based on age, gender and goal do the followings:
    // 10-35% protein
    // 45-65% carbs
    // 20-35% fat
    $Userage    = getAge($data);
    $Usergender = getGender($data);
    $Usergoal   = getGoal($data);
    $BMR        = calculateBmr($data);

    // factor in the user's age
    if($Userage < 20) {
        $ageFactor = 0.95;
    } elseif($Userage >= 20 && $Userage < 30) {
        $ageFactor = 1;
    } elseif($Userage >= 30 && $Userage < 40) {
        $ageFactor = 1.05;
    } elseif($Userage >= 40 && $Userage < 50) {
        $ageFactor = 0.95;
    } elseif($Userage >= 50) {
        $ageFactor = 0.9;
    }
    if($Usergender == 'Male'){
        if($Usergoal == 'Lose') {
            $p = 0.3 * $ageFactor;
            $c = 0.6 * (1 - $ageFactor);
            $f = 1 - $p - $c;
            $caloriDeficit = 500;
            $Macro =  [$p * $BMR - $caloriDeficit, $c * $BMR - $caloriDeficit, 
                       $f * $BMR - $caloriDeficit]; // [protein, carb, fat];
        } elseif($Usergoal == 'Gain') {
            $p = 0.3 * $ageFactor;
            $c = 0.6 * (1 - $ageFactor);
            $f = 1 - $p - $c;
            $caloriSurplus = 500;
            $Macro =  [$p * $BMR + $caloriSurplus, $c * $BMR + $caloriSurplus, 
                       $f * $BMR + $caloriSurplus]; // [protein, carb, fat];
        } else {
            $p = 0.3 * $ageFactor;
            $c = 0.6 * (1 - $ageFactor);
            $f = 1 - $p - $c;
            $caloriSurplus = 500;
            $Macro =  [$p * $BMR + $caloriSurplus, $c * $BMR + $caloriSurplus, 
                       $f * $BMR + $caloriSurplus]; // [protein, carb, fat];            
        }
    } elseif($Usergender == 'Female'){
        if($Usergoal == 'Lose') {
            $p = 0.2 * $ageFactor;
            $c = 0.65 * (1 - $ageFactor);
            $f = 1 - $p - $c;
            $caloriDeficit = 500;
            $Macro =  [$p * $BMR - $caloriDeficit, $c * $BMR - $caloriDeficit, 
                       $f * $BMR - $caloriDeficit]; // [protein, carb, fat];
        } elseif($Usergoal == 'Gain') {
            $p = 0.25 * $ageFactor;
            $c = 0.65 * (1 - $ageFactor);
            $f = 1 - $p - $c;
            $caloriSurplus = 500;
            $Macro =  [$p * $BMR + $caloriSurplus, $c * $BMR + $caloriSurplus, 
                       $f * $BMR + $caloriSurplus]; // [protein, carb, fat];
        } else {
            $p = 0.25 * $ageFactor;
            $c = 0.65 * (1 - $ageFactor);
            $f = 1 - $p - $c;
            $caloriSurplus = 500;
            $Macro =  [$p * $BMR + $caloriSurplus, $c * $BMR + $caloriSurplus, 
                       $f * $BMR + $caloriSurplus]; // [protein, carb, fat];
        }
    }
    return($Macro);
}
function calculateMicro($data){
    // add Micro computation code
    // vitamin         = [A, B1, B2, B3, B5, B6, B7, B9, B12, C, D, E, K]
    // trace minerals  = [Calcium, Chromium, Copper, Fluoride, 
    //                    Iodine, Iron, Magnesium, Manganese, 
    //                    Molybdenum, Phosphorus, Potassium, Selenium, Sodium, Zinc, Chloride]
    $Userage    = getAge($data);
    $Usergender = getGender($data);
    $vNames = ['A Retinol', 'Thiamin B1', 'Riboflavin B2', 
                   'Niacin B3','Pantothenic Acid B5', 'B6', 'Biotin B7', 'Folate B9', 'B12',
                   'C', 'D', 'E', 'K'];
    $tNames = ['Calcium', 'Chromium', 'Copper', 'Fluoride', 'Iodine', 'Iron', 'Magnesium', 
               'Manganese', 'Molybdenum', 'Phosphorus', 'Potassium', 'Selenium', 'Sodium',
               'Zinc', 'Chloride'];
    // units vector for vitamins
    $vUnits = ['ug RAE', 'mg', 'mg', 'mg', 'mg', 'mg', 'ug', 'ug DFE', 'ug', 'mg','IU', 'IU', 'ug'];
    // units vector for trace minerals
    $tUnits = ['mg', 'ug', 'ug', 'mg', 'ug', 'mg', 'mg', 'mg', 'ug', 'mg', 'mg', 'ug', 'mg', 'mg', 'g'];
    if($Usergender == 'Male') {
        if($Userage > 70) {
            $vValues = [900, 1.2, 1.3, 16, 5, 1.3, 30, 400, 2.4, 90, 800, 22.5, 120];
            $tValues = [1200, 30, 900, 4, 150, 8, 420, 2.3, 45, 700, 4700, 55, 1200, 11, 3.1];
        } elseif($Userage >= 50 && $Userage <= 70) {
            $vValues = [900, 1.2, 1.3, 16, 5, 1.3, 30, 400, 2.4, 90, 600, 22.5, 120];
            $tValues = [1000, 30, 900, 4, 150, 8, 420, 2.3, 45, 700, 4700, 55, 1300, 11, 3.1];
        } else {
            $vValues = [900, 1.2, 1.3, 16, 5, 1.3, 30, 400, 2.4, 90, 600, 22.5, 120];
            $tValues = [1000, 35, 900, 4, 150, 8, 400, 2.3, 45, 700, 4700, 55, 1500, 11, 3.1];
        }
    } elseif($Usergender == 'Female') {
        if($Userage > 70) {
            $vValues = [700, 1.1, 1.1, 14, 5, 1.3, 30, 400, 2.4, 75, 800, 22.5, 90]; 
            $tValues = [1200, 20, 900, 3, 150, 8, 320, 1.8, 45, 700, 4700, 55, 1200, 8, 3.1];
        } elseif($Userage >= 50 && $Userage <= 70) {
            $vValues = [700, 1.1, 1.1, 14, 5, 1.3, 30, 400, 2.4, 75, 600, 22.5, 90];
            $tValues = [1200, 20, 900, 3, 150, 8, 320, 1.8, 45, 700, 4700, 55, 1300, 8, 3.1];
        } else {
            $vValues = [700, 1.1, 1.1, 14, 5, 1.3, 30, 400, 2.4, 75, 600, 22.5, 90];
            $tValues = [1000, 25, 900, 3, 150, 18, 310, 1.8, 45, 700, 4700, 55, 1500, 8, 3.1];
        }   
    } 
    $Micro = array('vNames' => $vNames,
                   'tNames' => $tNames,
                   'vValues' => $vValues,
                   'tValues' => $tValues,
                   'vUnits' => $vUnits,
                   'tUnits' => $tUnits);
    return($Micro);
}

function dataPrep($user_bmi, $user_bmr, $user_if, $user_macro, $user_micro){
    $data = array('status' => 0,
                 'bmi'   => $user_bmi,
                 'bmr'   => $user_bmr,
                 'if'    => $user_if,
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



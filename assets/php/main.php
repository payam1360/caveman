<?php
define("WEIGHT", 2);
define("HEIGHT", 3);
define("DBG", false);
define("MAX_cnt", 6);
// class user
class user {
    var $name;
    var $email;
    var $id;
    var $questions;
    function set_name($value) {
        $this->name = $value;
    }
    function get_name() {
        return $this->name;
    }
};


function saveUserDataIntoDB($Questions) {
       
    $servername  = "127.0.0.1";
    $loginname   = "root";
    $password    = "@Ssia123";
    $dbname      = "Users";
    $table1name  = "Users";
    // Create connection
    $conn        = new mysqli($servername, $loginname, $password, $dbname);
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    if(DBG) {
        echo "Connected successfully";
    }

    for($kk = 0; $kk < MAX_cnt; $kk++){
        $options = "";
        if($Questions[$kk]->options == ""){
        } else {
            for($kx = 0; $kx < count($Questions[$kk]->options); $kx++){
                $options = $options . "," . $Questions[$kk]->options[$kx];
            }
        }
        $sql = "INSERT INTO " . $table1name . " (userid, Qidx, type, question, answer, options) VALUES('" . $Questions[$kk]->userid . "','" .  $Questions[$kk]->Qidx . "','" . $Questions[$kk]->type . "','" . $Questions[$kk]->question . "','" . $Questions[$kk]->answer . "','" . $options . "')";
        if(DBG) {
            echo $sql;
        }
        
        if ($conn->query($sql) === TRUE and DBG) {
            echo "New record created successfully";
        } elseif(DBG) {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
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
function calculateMeals(){
    
    $info0 = "this is info0";
    $info1 = "this is info1";
    $info2 = "this is info2";

    $user_meal = array('info0' => $info0,
                       'info1' => $info1,
                       'info2' => $info2);
// calculate intermittent fasting interval
    return($user_meal);
}
function dataPrep($user_bmi, $user_if, $user_macro, $user_micro, $user_meal){
    $data = array('ok' => true,
                 'bmi' => $user_bmi,
                 'If'  => $user_if,
                 'macro' => $user_macro,
                 'micro' => $user_micro,
                 'mealData' => $user_meal);
    return $data;
}

/// -------------------------
/// main routin starts here.
/// -------------------------
$userdata      = json_decode($_POST['userInfo']);
$dbflag        = saveUserDataIntoDB($userdata);
if($dbflag == false and DBG) {
    echo "user has not registered. no data is saved";
}elseif(DBG){
    echo "user data is saved.\n";
}

$user_bmi      = calculateBmi($userdata[WEIGHT]->answer, $userdata[HEIGHT]->answer);
$user_if       = calculateIf($userdata[WEIGHT]->answer, $userdata[HEIGHT]->answer);
$user_macro    = calculateMacro($userdata[WEIGHT]->answer, $userdata[HEIGHT]->answer);
$user_micro    = calculateMicro($userdata[WEIGHT]->answer, $userdata[HEIGHT]->answer);
$user_meal     = calculateMeals();
$data          = dataPrep($user_bmi, $user_if, $user_macro, $user_micro, $user_meal);

echo json_encode($data);


?>


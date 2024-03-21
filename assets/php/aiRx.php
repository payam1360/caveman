<?php
// getting the message
require_once "functions.php";
$data      = json_decode($_POST['userInfo']);
// decode user info
$gender = getGender($data); // male female
$age    = getAge($data);    // young mid old
$height = getHeight($data); // tall mid short
$weight = getWeight($data); // fat mid skinny
$goal   = getGoal($data);   // lose, gain, 
$stress = getStress($data); // lose, gain, 
$sleep  = getSleep($data);  // lose, gain, 


$servername  = "127.0.0.1";
$loginname   = "root";
$password    = "@Ssia123";
$dbname      = "Users";
$tablename   = "ai";
// Create connection
$conn        = new mysqli($servername, $loginname, $password, $dbname);
// check if the client exists.
$sql         = "SELECT meal FROM $tablename WHERE 
                                                    age    = '$age'    AND 
                                                    gender = '$gender' AND
                                                    height = '$height' AND
                                                    weight = '$weight' AND
                                                    goal   = '$goal'   AND
                                                    stress = '$stress'  AND
                                                    sleep  = '$sleep';";
$db_out   = $conn->query($sql);
$meal     = $db_out->fetch_assoc();

if(is_null($meal['meal'])) {
    $meal['meal'] = '';
}

session_start();
$_SESSION['meal']    = json_encode($meal['meal']); // this is either empty or it is already filled by the language model
$_SESSION['gender']  = $gender; 
$_SESSION['age']     = $age; 
$_SESSION['height']  = $height; 
$_SESSION['weight']  = $weight; 
$_SESSION['goal']    = $goal; 
$_SESSION['stress']  = $stress; 
$_SESSION['sleep']   = $sleep; 

?>
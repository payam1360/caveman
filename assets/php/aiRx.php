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

$mealPath = '../../descContent/' . $age . $gender . $height . $weight . $goal . $stress . $sleep . '.txt';
if(file_exists($mealPath)){
    $desc = [file_get_contents($mealPath)];
} else {
    $desc = ['DONE'];
}

session_start();
$_SESSION['Meal']['meal']    = $desc; // this is either empty or it is already filled by the language model
$_SESSION['Meal']['gender']  = $gender; 
$_SESSION['Meal']['age']     = $age; 
$_SESSION['Meal']['height']  = $height; 
$_SESSION['Meal']['weight']  = $weight; 
$_SESSION['Meal']['goal']    = $goal; 
$_SESSION['Meal']['stress']  = $stress; 
$_SESSION['Meal']['sleep']   = $sleep; 
$_SESSION['Meal']['type']    = 'Meal';
?>
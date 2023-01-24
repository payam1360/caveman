<?php

define("DBG", false);
define("MAX_cnt", 3);


function getPublicForm() {
    $data['qContent'] = ["1. Hey there, what is your name?", "2. Hi #Name, what is your nutritional goal?", "3. How much do you weigh?", "4. How tall are you?", "5. How is your sleep?"];
    $data['qType'] = ["text", "button", "list", "list", "button"];
    $data['qIdx'] = [0, 1, 2, 3, 4];
    $data['options'] = ["", ["fa-solid fa-weight-scale","fa-solid fa-dumbbell","fa-solid fa-heart-pulse"],['80lb-90lb','90lb-100lb','100lb-110lb','110lb-120lb','120lb-130lb','130lb-140lb','140lb-150lb','150lb-160lb','160lb-170lb','170lb-180lb','180lb-190lb','190lb-200lb','200lb-210lb','210lb-220lb','220lb-230lb','230lb-240lb','240lb-250lb','250lb+'],['<5ft','5ft-5.1ft','5.1ft-5.2ft','5.2ft-5.3ft','5.3ft-5.4ft','5.4ft-5.5ft','5.5ft-5.6ft','5.6ft-5.7ft','5.7ft-5.8ft','5.8ft-5.9ft','5.9ft-5.10ft','5.10ft-5.11ft','5.11ft-6.0ft','6.0ft-6.1ft','6.1ft-6.2ft','6.2ft-6.3ft','6.3ft-6.4ft','6.4ft-6.5ft', '6.5ft+'],["fa-solid fa-moon","fa-solid fa-face-tired"]];
    $data['optionsText'] = ["", ["lose weight","gain muscles","be less tired"],[""],[""],["7-8 hours a day","always tired"]];;
    $data['qRequired'] = [1, 1, 1, 1, 1];
    $data['MAX_cnt'] = 5;
    return $data;
}

// set login form
function getLoginForm() {
    $data['qContent'] = ["1. enter your email:", "2. enter your password:"];
    $data['qType'] = ["email", "password"];
    $data['qIdx'] = [0, 1];
    $data['options'] = ["", ""];
    $data['optionsText'] = ["", ""];
    $data['qRequired'] = [1, 1];
    $data['MAX_cnt'] = 2;
    return $data;
}
function getRegisterForm() {
    $data['qContent'] = ["1/3. enter your email:", "2/3. enter your password:", "3/3. re-enter your password:"];
    $data['qType'] = ["email", "password", "password"];
    $data['qIdx'] = [0, 1, 2];
    $data['options'] = ["", "", ""];
    $data['optionsText'] = ["", "", ""];
    $data['qRequired'] = [1, 1, 1];
    $data['MAX_cnt'] = 3;
    return $data;
}

function firstQuestionBackendForm() {
    $data['qContent'] = ["1. what is the TYPE of question you want to ask your client?"];
    $data['qType'] = ["button"];
    $data['qIdx'] = [0];
    $data['options'] = [["fa-solid fa-list-ul", "fa-solid fa-comment-dots", "fa-solid fa-hand-pointer", "fa-solid fa-envelope"]];
    $data['optionsText'] = [["list options", "write text", "multiple choice", "email"]];
    $data['qRequired'] = [1, 1, 1, 1];
    $data['MAX_cnt'] = 1;
    return $data;
}

function analysisForm() {
    $data['qContent'] = ["1. How do you want food analysis be done? (Macro/Mico nutrients)", "2. How do you want meal planning be done?", "3. How do like to perform sleep analysis for your clients"];
    $data['qType'] = ["button", "button", "button"];
    $data['qIdx'] = [0, 1, 2];
    $data['options'] = [["fa-solid fa-brain", "fa-brands fa-nutritionix"], ["fa-solid fa-brain", "fa-brands fa-nutritionix"], ["fa-solid fa-brain", "fa-brands fa-nutritionix"]];
    $data['optionsText'] = [["AI", "Nutritionist"], ["AI", "Nutritionist"], ["AI", "Nutritionist"]];
    $data['qRequired'] = [1, 1, 1];
    $data['MAX_cnt'] = 3;
    return $data;
}
/// -------------------------
/// main routin starts here.
/// -------------------------
$page        = json_decode($_POST['request']);
if($page == 'login') {
    $data      = getLoginForm();
} elseif($page == 'register') {
    $data      = getRegisterForm();
} elseif($page == 'main') {
    $data      = getPublicForm();
} elseif($page == 'questions') {
    $data      = firstQuestionBackendForm();
} elseif($page == 'analysis') {
    $data      = analysisForm();
}
echo json_encode($data);


?>


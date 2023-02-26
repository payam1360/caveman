<?php

define("DBG", false);
define("MAX_cnt", 3);


function getPublicForm() {
    $data['qContent'] = [
                            ["1. Hey there, what is your name?"],
                            ["2. Hi #mainNameTag, what is your nutritional goal?"],
                            ["3. How much do you weigh?"],
                            ["4. How tall are you?"],
                            ["5. How is your sleep?"],
                            [""]
                        ];
    $data['qType'] = [
                        ["text"],
                        ["button"],
                        ["list"],
                        ["list"],
                        ["button"],
                        ["message"]
                    ];
    $data['qIdx'] = [0, 1, 2, 3, 4, 5];
    
    $data['options'] = [
                            [[""]],
                            [["fa-solid fa-weight-scale","fa-solid fa-dumbbell","fa-solid fa-heart-pulse"]],
                            [['80lb-90lb','90lb-100lb','100lb-110lb','110lb-120lb','120lb-130lb','130lb-140lb','140lb-150lb','150lb-160lb', '160lb-170lb','170lb-180lb','180lb-190lb','190lb-200lb','200lb-210lb','210lb-220lb','220lb-230lb','230lb-240lb','240lb-250lb','250lb+']],
                            [['<5ft','5ft-5.1ft','5.1ft-5.2ft','5.2ft-5.3ft','5.3ft-5.4ft','5.4ft-5.5ft','5.5ft-5.6ft','5.6ft-5.7ft', '5.7ft-5.8ft','5.8ft-5.9ft','5.9ft-5.10ft','5.10ft-5.11ft','5.11ft-6.0ft','6.0ft-6.1ft','6.1ft-6.2ft','6.2ft-6.3ft','6.3ft-6.4ft','6.4ft-6.5ft', '6.5ft+']],
                            [["fa-solid fa-moon","fa-solid fa-face-tired"]],
                            [["fa-regular fa-circle-check"]]
                       ];
    
    $data['optionsText'] = [
                                [[""]],
                                [["lose weight","gain muscles","be less tired"]],
                                [[""]],
                                [[""]],
                                [["7-8 hours a day","always tired"]],
                                [["All Done!"]]
                           ];
    
    $data['qRequired'] = [1, 1, 1, 1, 1, 0];
    $data['MAX_cnt'] = 6;
    return $data;
}

// set login form
function getLoginForm() {
    $data['qContent'] = [
                            ["1. enter your email:"],
                            ["2. enter your password:"],
                            [""]
                        ];
    $data['qType'] = [
                        ["email"],
                        ["password"],
                        ["message"]
                     ];
    $data['qIdx'] = [0, 1, 2];
    
    $data['options'] = [
                            [[""]],
                            [[""]],
                            [["fa-regular fa-circle-check"], ["fa-solid fa-xmark"], ["fa-solid fa-signature"]]
                       ];
    $data['optionsText'] = [
                                [[""]],
                                [[""]],
                                [["logging in ..."], ["wrong password"], ["user not found, please register!"]]
                           ];
    
    $data['qRequired'] = [1, 1, 0];
    $data['MAX_cnt'] = 3;
    return $data;
}
function getRegisterForm() {
    $data['qContent'] = [
                            ["1. enter your name:"],
                            ["2. Hi #nameRegister, what is your email?"],
                            ["please on the next screen, enter the verification code we just emailed you:"],
                            ["3. verification code:"],
                            [""],
                            ["4. enter your password:"],
                            ["5. re-enter your password:"], [""]
                        ];
    $data['qType'] = [
                        ["text"],
                        ["email"],
                        ["message"],
                        ["text"],
                        ["message"],
                        ["password"],
                        ["password"],
                        ["message"]
                    ];
    
    $data['qIdx'] = [0, 1, 2, 3, 4, 5, 6, 7];
    
    $data['options'] = [
                            [[""]],
                            [[""]],
                            [["fa-regular fa-registered"], ["fa-solid fa-user-tie"]],
                            [[""]],
                            [["fa-solid fa-xmark"], ["fa-regular fa-circle-check"]],
                            [[""]],
                            [[""]],
                            [["fa-solid fa-not-equal"],["fa-regular fa-circle-check"]]]
    ;
    $data['optionsText'] = [
                                [[""]],
                                [[""]],
                                [["user already registered"], ["new user"]],
                                [[""]],
                                [["incorrect code"], ["continue to password"]],
                                [[""]],
                                [[""]],
                                [["password not matching"], ["All done! please login"]]
                            ];
    $data['qRequired'] = [1, 1, 1, 1, 1, 1, 1, 1];
    $data['MAX_cnt'] = 8;
    return $data;
}

function QuestionBackendForm() {
        
    $data['qContent'] = [
                            ["1. what is the TYPE of question you want to ask your client?"],
                            [""],
                            ["2. is this question REQUIRED to be answered by the client?"],
                            ["3. are you done?","3. type the body of the question?"],
                            ["4. what is the purpose of your question?"],
                            ["5. are you done?", "5. enter your items description seperated by SEMICOLON (;)"],
                            ["6. are you done?", "6. enter your choice icons seperated by SEMICOLON (;)"],
                            ["7. are you done?"],
                            [""]
                        ];
    $data['qType'] = [
                        ["button"],
                        ["message"],
                        ["button"],
                        ["button","text"],
                        ["list"],
                        ["button", "text"],
                        ["button", "text"],
                        ["button"],
                        ["message"]
                    ];
    
    $data['qIdx'] = [0, 1, 2, 3, 4, 5, 6, 7, 8];
    
    $data['options'] = [
                            [["fa-solid fa-list-ul", "fa-solid fa-comment-dots", "fa-solid fa-hand-pointer", "fa-solid fa-envelope"]],
                            [["fa-solid fa-gears"], ["fa-regular fa-hand-point-right"]],
                            [['fa-regular fa-thumbs-up','fa-regular fa-thumbs-down']],
                            [['fa-regular fa-thumbs-up','fa-regular fa-thumbs-down'], [""]],
                            [["client water intake","client calories intake", "client weight", "client height", "client macros"]],
                            [['fa-regular fa-thumbs-up','fa-regular fa-thumbs-down'], [""]],
                            [['fa-regular fa-thumbs-up','fa-regular fa-thumbs-down'], [""]],
                            [['fa-regular fa-thumbs-up','fa-regular fa-thumbs-down']],
                            [['fa-regular fa-circle-check']],
                       ];
    
    $data['optionsText'] = [
                                [["list options", "write text", "multiple choice", "email"]],
                                [["your form is being built ... please continue!"], ["please answer the prevous question"]],
                                [["YES", "NO"]],
                                [["YES", "NO"], [""]],
                                [[""]],
                                [["YES", "NO"], [""]],
                                [["YES", "NO"], [""]],
                                [["YES", "NO"]],
                                [["Your campaign is added to your account."]]
                           ];
    
    $data['qRequired'] = [1, 0, 0, 1, 1, 1, 1, 1, 1];
    $data['MAX_cnt'] = 9;
    return $data;
}

function analysisForm() {
    $data['qContent'] = [
                            ["1. How do you want food analysis be done? (Macro/Mico nutrients)"],
                            ["2. How do you want meal planning be done?"],
                            ["3. How do like to perform sleep analysis for your clients"]
                        ];
    $data['qType'] = [
                            ["button"],
                            ["button"],
                            ["button"]
                     ];
    $data['qIdx'] = [0, 1, 2];
    $data['options'] = [
                            [["fa-solid fa-brain", "fa-brands fa-nutritionix"]],
                            [["fa-solid fa-brain", "fa-brands fa-nutritionix"]],
                            [["fa-solid fa-brain", "fa-brands fa-nutritionix"]]
                       ];
    $data['optionsText'] = [
                                [["AI", "Nutritionist"]],
                                [["AI", "Nutritionist"]],
                                [["AI", "Nutritionist"]]
    ];
    $data['qRequired'] = [1, 1, 1];
    $data['MAX_cnt'] = 3;
    return $data;
}

function clientsSearchForm() {
    $data['qContent'] = [
                            ["1. search clients:"],
                            ["2. client's #clientsTag"]
                        ];
    $data['qType'] = [
                        ["button"],
                        ["text"]
                     ];
    $data['qIdx'] = [0, 1];
    $data['options'] = [
                            [["fa-solid fa-fingerprint", "fa-regular fa-envelope", "fa-solid fa-signature"]],
                            [[""]]
                       ];
    $data['optionsText'] = [
                                [["Client's ID", "Client's email", "Client's name"]],
                                [[""]]
                           ];
    $data['qRequired'] = [1, 1];
    $data['MAX_cnt'] = 2;
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
    $data      = QuestionBackendForm();
} elseif($page == 'analysis') {
    $data      = analysisForm();
} elseif($page == 'clients') {
    $data      = clientsSearchForm();
}
echo json_encode($data);


?>


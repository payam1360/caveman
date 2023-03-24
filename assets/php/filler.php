<?php



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
    $data['qKey'] = [
                        [''],
                        ['gain muscle'],
                        ['weight'],
                        ['height'],
                        ['sleep'],
                        ['']
                    ];
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
    $data['qKey'] = [
                        ['email'],
                        ['password'],
                        ['']
                    ];
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
                            ["5. re-enter your password:"],
                            [""]
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
    $data['qKey'] = [
                        ['name'],
                        ['email'],
                        [''],
                        [''],
                        [''],
                        ['password'],
                        ['password'],
                        ['']
                    ];
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
    $data['qKey'] = [
                        [''],
                        [''],
                        [''],
                        [''],
                        [''],
                        [''],
                        [''],
                        [''],
                        ['']
                    ];
    $data['MAX_cnt'] = 9;
    return $data;
}

function addClients() {
    $data['qContent'] = [
                            ["1. What is your client's name? (optional)"],
                            ["2. What is your client's gender?"],
                            ["3. What is your client's goal?"],
                            ["4. How do you want nutrition analysis be done?"],
                            ["5. How do you want meal planning be done?"],
                            [""]
                        ];
    $data['qType'] = [
                            ["text"],
                            ["button"],
                            ["button"],
                            ["button"],
                            ["button"],
                            ["message"]
                     ];
    $data['qIdx'] = [0, 1, 2, 3, 4, 5];
    $data['options'] = [
                            [[""]],
                            [["fa-solid fa-mars", "fa-solid fa-venus", "fa-solid fa-question"]],
                            [["fa-solid fa-arrow-up-right-dots", "fa-solid fa-hand-fist", "fa-solid fa-weight-scale"]],
                            [["fa-solid fa-brain", "fa-brands fa-nutritionix"]],
                            [["fa-solid fa-brain", "fa-brands fa-nutritionix"]],
                            [["fa-regular fa-circle-check"]],
                       ];
    $data['optionsText'] = [
                                [[""]],
                                [["Male", "Female", 'I don\'t know']],
                                [["increase testosterone", "increase muscle mass", "lose weight"]],
                                [["AI", "Nutritionist"]],
                                [["AI", "Nutritionist"]],
                                [["New client is created!"]]
                           ];
    $data['qRequired'] = [0, 1, 1, 1, 1, 1];
    $data['qKey'] = [
                        ['name'],
                        ['gender'],
                        ['goal'],
                        ['engine'],
                        ['engine'],
                        ['']
                    ];
    $data['MAX_cnt'] = 6;
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
    $data['qKey'] = [
                        [''],
                        ['']
                    ];
    $data['MAX_cnt'] = 2;
    return $data;
}




function clientPageLoad($page) {
    session_start();
    $userId      = substr($page, 0, 6);
    $clientId    = substr($page, 6, 5);
    $campaignId  = substr($page, 11, 7);
    $servername  = "127.0.0.1";
    $loginname   = "root";
    $password    = "@Ssia123";
    $dbname      = "Users";
    $tablename   = "Users";
    $conn        = new mysqli($servername, $loginname, $password, $dbname);
    $sql         = "SELECT qContent, qType, qIdx, options, optionsText, qRequired, qKey FROM $tablename WHERE userId = '$userId' AND clientId = '$clientId' AND campaignId = '$campaignId';";
    $info = $conn->query($sql);
    $num_rows = $info->num_rows;
    $pulledRow = $info->fetch_assoc();
    
    $qContent = array(array($pulledRow['qContent']));
    $qType = array(array($pulledRow['qType']));
    $qIdx = array(0);
    $options = array(array(array($pulledRow['options'])));
    $optionsText = array(array(array($pulledRow['optionsText'])));
    $qRequired = array((int)$pulledRow['qRequired']);
    $qKey = array(array($pulledRow['qKey']));
    
    for($kk = 1; $kk < $num_rows; $kk++) {
        $pulledRow = $info->fetch_assoc();
        array_push($qContent, array($pulledRow['qContent']));
        array_push($qType, array($pulledRow['qType']));
        array_push($qIdx, $kk);
        array_push($options, array(array($pulledRow['options'])));
        array_push($optionsText, array(array($pulledRow['optionsText'])));
        array_push($qRequired, (int)$pulledRow['qRequired']);
        array_push($qKey, array($pulledRow['qKey']));
    }
    array_push($qContent, array(""));
    array_push($qType, array('message'));
    array_push($qIdx, $kk);
    array_push($options, array(array('fa-regular fa-thumbs-up')));
    array_push($optionsText, array(array('view your results below!')));
    array_push($qRequired, 0);
    array_push($qKey, array(''));

    $data['qContent'] = $qContent;
    $data['qType'] = $qType;
    $data['qIdx'] = $qIdx;
    $data['options'] = $options;
    $data['optionsText'] = $optionsText;
    $data['qRequired'] = $qRequired;
    $data['qKey'] = $qKey;

    $data['MAX_cnt'] = $num_rows+1;
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
} elseif($page == 'addClients') {
    $data      = addClients();
} elseif($page == 'clients') {
    $data      = clientsSearchForm();
} else {
    $data      = clientPageLoad($page);
}
echo json_encode($data);


?>


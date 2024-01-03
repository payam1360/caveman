<?php

$supportedIcons = ["fa-solid fa-dumbbell","fa-solid fa-heart-pulse", 
"fa-regular fa-face-angry","fa-regular fa-face-meh","fa-regular fa-face-smile", 
"fa-regular fa-moon","fa-regular fa-face-tired", "fa-solid fa-mars","fa-solid fa-venus", 
"fa-regular fa-circle-check", "fa-solid fa-xmark", 
"fa-solid fa-signature", "fa-regular fa-registered", "fa-solid fa-user-tie", 
"fa-solid fa-not-equal", 
"fa-solid fa-list-ul", "fa-solid fa-comment-dots", "fa-solid fa-hand-pointer", "fa-solid fa-envelope", 
"fa-solid fa-gears", "fa-regular fa-hand-point-right", "fa-regular fa-thumbs-up",'fa-regular fa-thumbs-down',
"fa-solid fa-weight-scale", "fa-solid fa-text-height", "fa-solid fa-person-cane", "fa-solid fa-water", "fa-solid fa-droplet",
"fa-solid fa-droplet-slash", "fa-solid fa-arrow-up-right-dots", "fa-solid fa-brain", "fa-brands fa-nutritionix", 
"fa-solid fa-hand-fist", "fa-solid fa-question", "fa-solid fa-person-running", "fa-solid fa-person-walking", "fa-solid fa-bowl-food",
"fa-solid fa-martini-glass-citrus", "fa-solid fa-fish", "fa-solid fa-cubes-stacked"];

$supportedText = ["gain muscle", "increase heart rate","always tired", "be less tired", "relaxed",
                  "7-8 hour sleep", "get less sleep", "Male", "Female", "great", "bad", 
                  "sign up", "register", "new user", "not equal", "list", "text", 
                  "multiple choice", "email", "work process", "proceed", "ok", "not ok",
                  "lose weight", "height", "age",  "drink lot of water", "drink enough water", 
                  "drink less water", "increase testostrone", "AI", "nutritionist", "intense workout",
                  "don't know", "Cardio", "walking", "enough calories", "alcohol", "omega 3", "sugar"];

$supportedAge = [  '18','19','20','21','22','23','24','25', '26','27','28','29','30','31',
                   '32','33','34','35','36','37','38','39','40','41','42','43','44','45', 
                   '46','47','48','49','50','51','52','53','54','55', '56','57','58','59',
                   '60','61','62','63','64','65','66','67','68','69','70','71','72','73',
                   '74','75', '76','77','78','79','80','81','82','83','84','85', '86','87',
                   '88','89','90>'];

$supportedAgeDemo = ['18, 19, ...'];



$supportedWeight =  [  '<80','81','82','83','84','85','86','87', '88','89','90','91','92',
                       '93','94','95','96','97', '98','99','100','101','102','103','104',
                       '105','106','107','108','109','110','111','112','113','114','115',
                       '116','117','118','119','120','121','122','123','124','125','126',
                       '127','128','129','130','131','132','133','134','135','136','137',
                       '138','139','140','141','142','143','144','145','146','147','148',
                       '149','150','151','152','153','154','155','156','157','158','159',
                       '160','161','162','163','164','165','166','167','168','169','170',
                       '171','172','173','174','175','176','177','178','179','180','181',
                       '182','183','184','185','186','187','188','189','190','191','192',
                       '193','194','195','196','197','198','199','200','201','202','203',
                       '204','205','206','207','208','209','210','211','212','213','214',
                       '215','216','217','218','219','220','221','222','223','224','225',
                       '226','227','228','229','230','231','232','233','234','235','236',
                       '237','238','239','240', '240>'];

$supportedWeightDemo =  ['80, 81, ...'];

$supportedHeight =  [   "<5", "5", "5-1", "5-2", "5-3", "5-4", "5-5", "5-6", "5-6", "5-7", 
                        "5-8", "5-9", "5-10", "5-11", "6", "6-1", "6-2", "6-3", "6-4", "6-5", 
                        "6-6", "6-6", "6-7", "6-8", "6-9", "6-10", "6-11", "7>"];                                      

$supportedHeightDemo =  ['5, 5-1 ...'];                                      

function getPublicForm() {
    global $supportedHeight;
    global $supportedWeight;
    global $supportedAge;

    $data['qContent'] = [
                            ["1. Hey there, what is your name?"],
                            ["2. Hi #mainNameTag, what is your nutritional goal?"],
                            ["3. How much do you weigh? (lb)"],
                            ["4. How tall are you? (ft-in)"],
                            ["5. What is your age?"],
                            ["6. How is your stress level?"],
                            ["7. How is your sleep?"],
                            ["8. And finally, you identify as:"],
                            [""]
                        ];
    $data['qType'] = [
                        ["text"],
                        ["button"],
                        ["list"],
                        ["list"],
                        ["list"],
                        ["button"],
                        ["button"],
                        ["button"],
                        ["message"]
                    ];
    $data['qIdx'] = [0, 1, 2, 3, 4, 5, 6, 7, 8];
    $data['options'] = [
                            [[""]],
                            [["fa-solid fa-weight-scale","fa-solid fa-dumbbell","fa-solid fa-heart-pulse"]],
                            [$supportedWeight],
                            [$supportedHeight],
                            [$supportedAge],
                            [["fa-regular fa-face-angry","fa-regular fa-face-meh","fa-regular fa-face-smile"]],
                            [["fa-regular fa-moon","fa-regular fa-face-tired"]],
                            [["fa-solid fa-mars","fa-solid fa-venus"]],
                            [["fa-regular fa-circle-check"]]
                       ];
    
    $data['optionsText'] = [
                                [[""]],
                                [["lose weight","gain muscles","be less tired"]],
                                [[""]],
                                [[""]],
                                [[""]],
                                [["high","manageable","relaxed"]],
                                [["7-8 hours a day","always tired"]],
                                [["Male","Female"]],
                                [["All Done!"]]
                           ];
    
    $data['qRequired'] = [1, 1, 1, 1, 1, 1, 1, 1, 0];
    $data['qKey'] = [
                        ['name'],
                        ['goal'],
                        ['weight'],
                        ['height'],
                        ['age'],
                        ['stress'],
                        ['sleep'],
                        ['gender'],
                        ['']
                    ];
    $data['MAX_cnt'] = 9;
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
                                [["password not matching"], ["All done! please <a style=\"color: dodgerblue; text-decoration: underline;\" href=login.html>login</a>"]]
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
        
    global $supportedIcons;
    global $supportedText;
    global $supportedAgeDemo;
    global $supportedWeightDemo;
    global $supportedHeightDemo;

    $data['qContent'] = [
                            ["1. what is the TYPE of question you want to ask your client?"],
                            [""],
                            ["2. is this question REQUIRED to be answered by the client?"],
                            ["3. are you done?","3. type the body of the question?"],
                            ["", "4. what is the PURPOSE of your question?"],
                            ["5. are you done?", "5. select your list topic!", "5. Multi-select your button options!"],
                            ["", "6. are you done?"],
                            [""]
                        ];
    $data['qType'] = [
                        ["button"],
                        ["message"],
                        ["button"],
                        ["button","text"],
                        ["message", "list"],
                        ["button", "multiButton", "multiButton"],
                        ["message", "button"],
                        ["message"]
                    ];
    
    $data['qIdx'] = [0, 1, 2, 3, 4, 5, 6, 7];
    
    $data['options'] = [
                            [["fa-solid fa-list-ul", "fa-solid fa-comment-dots", "fa-solid fa-hand-pointer", "fa-solid fa-envelope"]],
                            [["fa-solid fa-gears"], ["fa-regular fa-hand-point-right"]],
                            [['fa-regular fa-thumbs-up','fa-regular fa-thumbs-down']],
                            [['fa-regular fa-thumbs-up','fa-regular fa-thumbs-down'], [""]],
                            [['fa-regular fa-circle-check'], ["name", "water", "calories", "weight", "height", "age", "gender", "goal", "macros", "micros", "sleep", "workout", "stress", "sugar", "other"]],
                            [['fa-regular fa-thumbs-up','fa-regular fa-thumbs-down'], ["fa-solid fa-weight-scale", "fa-solid fa-text-height", "fa-solid fa-person-cane"],
                             $supportedIcons],
                            [['fa-regular fa-circle-check'], ['fa-regular fa-thumbs-up','fa-regular fa-thumbs-down']],
                            [['fa-regular fa-circle-check']],
                       ];
    
    $data['optionsText'] = [
                                [["list options", "write text", "multiple choice", "email"]],
                                [["your form is being built ... please continue!"], ["please answer the prevous question"]],
                                [["YES", "NO"]],
                                [["YES", "NO"], [""]],
                                [["Your campaign is added to your account."], [""]],
                                [["YES", "NO"], [$supportedWeightDemo, $supportedHeightDemo, $supportedAgeDemo], $supportedText],
                                [["Your campaign is added to your account."], ["YES", "NO"]],
                                [["Your campaign is added to your account."]]
                           ];
    
    $data['qRequired'] = [1, 0, 0, 1, 1, 1, 1, 1];
    $data['qKey'] = [
                        [''],
                        [''],
                        [''],
                        [''],
                        [''],
                        [''],
                        [''],
                        ['']
                    ];
    $data['MAX_cnt'] = 8;
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
                            [["fa-solid fa-arrow-up-right-dots", "fa-solid fa-hand-fist", "fa-solid fa-weight-scale", "fa-solid fa-question"]],
                            [["fa-solid fa-brain", "fa-brands fa-nutritionix"]],
                            [["fa-solid fa-brain", "fa-brands fa-nutritionix"]],
                            [["fa-regular fa-circle-check"]],
                       ];
    $data['optionsText'] = [
                                [[""]],
                                [["Male", "Female", 'I don\'t know']],
                                [["increase testosterone", "increase muscle mass", "lose weight", 'I am not sure']],
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
    global $supportedIcons;
    global $supportedText;
    
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
    $options = array();
    $optionsText = array();
    // fixing options
    if($pulledRow['options'] != "") {
        $options = array(array(explode(',', $pulledRow['options'])));
    } else {
        array_push($options,array(array("")));
    }
    // fixing options text
    if($pulledRow['optionsText'] != "") {
        $optionsText = array(array(explode(',', $pulledRow['optionsText'])));
    } else {
        array_push($optionsText,array(array("")));
    }
    // get the rest of parameters
    $qRequired = array((int)$pulledRow['qRequired']);
    $qKey = array(array($pulledRow['qKey']));
    
    for($kk = 1; $kk < $num_rows; $kk++) {
        $pulledRow = $info->fetch_assoc();
        array_push($qContent, array($pulledRow['qContent']));
        array_push($qType, array($pulledRow['qType']));
        array_push($qIdx, $kk);
        // fixing options
        if($pulledRow['options'] != "") {
            array_push($options, array(explode(',', $pulledRow['options'])));
        } else {
            array_push($options,array(array("")));
        }
        // fixing options text
        if($pulledRow['optionsText'] != "") {
            array_push($optionsText, array(explode(',', $pulledRow['optionsText'])));
        } else {
            array_push($optionsText,array(array("")));
        }
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
$request        = json_decode($_POST['request']);
$page           = $request->page;
$landing        = $request->address;
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

// -------
if(strpos($landing, 'userId') == "") {
    if($landing == "") {
        $userIdURL = "0";
        $clientIdURL = "0";
        $campaignIdURL = "0";
    } else { // coming from the clientPageLoad
        $userIdURL = substr($page, 0, 6);
        $clientIdURL = substr($page, 6, 5);
        $campaignIdURL = substr($page, 11, 7);
    }
} else {
    $userIdIdx        = strpos($landing, 'userId');
    $clientIdIdx      = strpos($landing, 'clientId');
    $campaignIdIdx    = strpos($landing, 'campaignId');

    $userIdLength     = 6;
    $clientIdLength   = 5;
    $campaignIdLength = 7;

    $userIdURL        = substr($landing, $userIdIdx+7, $userIdLength);
    $clientIdURL      = substr($landing, $clientIdIdx+9, $clientIdLength);
    $campaignIdURL    = substr($landing, $campaignIdIdx+11, $campaignIdLength);
}

$data['userid'] = $userIdURL;
$data['clientId'] = $clientIdURL;
$data['campaignId'] = $campaignIdURL;
// -------

echo json_encode($data);


?>


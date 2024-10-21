<?php


$supportedIcons = [ 
                    "fa-solid fa-water","fa-solid fa-droplet","fa-solid fa-droplet-slash", // water
                    "fa-solid fa-leaf", "fa-solid fa-candy-cane", "fa-solid fa-cubes-stacked", // sugar
                    "fa-solid fa-wine-glass-empty", "fa-solid fa-beer-mug-empty", "fa-solid fa-wine-bottle" // alcohol
                ];

$supportedText = [
                  "drink lot of water", "drink enough water", "drink less water",
                  "less sugar", "some sugar", "lots of sugar",
                  "no alcohol", "sometimes", "regularly"];

$supportedAge = [  '18','19','20','21','22','23','24','25', '26','27','28','29','30','31',
                   '32','33','34','35','36','37','38','39','40','41','42','43','44','45', 
                   '46','47','48','49','50','51','52','53','54','55', '56','57','58','59',
                   '60','61','62','63','64','65','66','67','68','69','70','71','72','73',
                   '74','75', '76','77','78','79','80','81','82','83','84','85', '86','87',
                   '88','89','90>'];



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


$supportedHeight =  [   "<5", "5", "5-1", "5-2", "5-3", "5-4", "5-5", "5-6", "5-7", 
                        "5-8", "5-9", "5-10", "5-11", "6", "6-1", "6-2", "6-3", "6-4", "6-5", 
                        "6-6", "6-6", "6-7", "6-8", "6-9", "6-10", "6-11", "7>"];                                      


$supportedCalories = ['1000'];
for($cCounter = 1100; $cCounter < 3100; $cCounter += 100){
    array_push($supportedCalories, strval($cCounter)); 
}
$supportedCaloriesDemo = ['1kcal,...'];
$supportedWorkout =  [  'never', '1hr a week', '3hrs a week', '5hrs a week', '7hrs a week'];
$supportedWorkoutDemo =  ['workout 1hr a week,  ...'];


function getPublicForm() {
    global $supportedHeight;
    global $supportedWeight;
    global $supportedAge;

    $data['qContent'] = [
                            ["1. Hey there, what is your name?"],
                            ["2. Hi #dynomicContent, what is your target weight or goal?"],
                            ["3. How much do you weigh now? (lb)"],
                            ["4. How tall are you? (ft-in)"],
                            ["5. What is your age?"],
                            ["6. How is your stress level?"],
                            ["7. How is your sleep?"],
                            ["8. And you identify as:"],
                            ["9. #dynomicContent, will you share your email with us?"],
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
                        ["email"],
                        ["message"]
                    ];
    $data['qIdx'] = [0, 1, 2, 3, 4, 5, 6, 7, 8, 9];
    $data['options'] = [
                            [[""]],
                            [["fa-solid fa-weight-scale","fa-solid fa-dumbbell","fa-solid fa-heart-pulse"]],
                            [$supportedWeight],
                            [$supportedHeight],
                            [$supportedAge],
                            [["fa-regular fa-face-angry","fa-regular fa-face-meh","fa-regular fa-face-smile"]],
                            [["fa-regular fa-moon","fa-regular fa-face-tired"]],
                            [["fa-solid fa-mars","fa-solid fa-venus"]],
                            [[""]],
                            [["fa-regular fa-circle-check"]]
                       ];
    
    $data['optionsText'] = [
                                [[""]],
                                [["lose weight","gain muscle","be healthy"]],
                                [[""]],
                                [[""]],
                                [[""]],
                                [["high stress","medium stress","low stress"]],
                                [["well rested","get less sleep"]],
                                [["male","female"]],
                                [[""]],
                                [["All Done!"]]
                           ];
    
    $data['qRequired'] = [1, 1, 1, 1, 1, 1, 1, 1, 0, 0];
    $data['qKey'] = [
                        ['name'],
                        ['goal'],
                        ['weight'],
                        ['height'],
                        ['age'],
                        ['stress'],
                        ['sleep'],
                        ['gender'],
                        ['email'],
                        ['']
                    ];
    $data['MAX_cnt'] = 10;
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
                            [["fa-regular fa-circle-check", "fa-solid fa-xmark", "fa-solid fa-signature"]]
                       ];
    $data['optionsText'] = [
                                [[""]],
                                [[""]],
                                [["logging in ...", "wrong password", "user not found, please register!"]]
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
                            ["1. Which plan are you interested?"],
                            ["1. enter your name:"],
                            ["2. Hi #dynomicContent, what is your email?"],
                            ["please on the next screen, enter the verification code we just emailed you:"],
                            ["3. verification code:"],
                            [""],
                            ["4. enter your password:"],
                            ["5. re-enter your password:"],
                            ["", "Payment, powered by Stripe"],
                            [""]
                        ];
    $data['qType'] = [
                        ["button"],
                        ["text"],
                        ["email"],
                        ["message"],
                        ["text"],
                        ["message"],
                        ["password"],
                        ["password"],
                        ["message", "stripe"],
                        ["message"]
                    ];
    
    $data['qIdx'] = [0, 1, 2, 3, 4, 5, 6, 7, 8, 9];
    
    $data['options'] = [
                            [["fa-solid fa-0","fa-solid fa-sack-dollar"]],
                            [[""]],
                            [[""]],
                            [["fa-regular fa-registered", "fa-solid fa-user-tie"]],
                            [[""]],
                            [["fa-solid fa-xmark", "fa-regular fa-circle-check"]],
                            [[""]],
                            [[""]],
                            [["fa-solid fa-not-equal", "fa-regular fa-circle-check"],[""]],
                            [["fa-regular fa-circle-check"]]
                        ];
    $data['optionsText'] = [
                                [["free plan", "AI plan"]],
                                [[""]],
                                [[""]],
                                [["user already registered", "new user"]],
                                [[""]],
                                [["incorrect code", "continue to password"]],
                                [[""]],
                                [[""]],
                                [["password not matching", "All done! please <a style=\"color: dodgerblue; \" href=login.html> login</a>"],[""]],
                                [["All done! please <a style=\"color: dodgerblue;\" href=login.html> login</a>"]]
                            ];
    $data['qRequired'] = [1, 1, 1, 1, 1, 1, 1, 1, 1, 1];
    $data['qKey'] = [
                        ['plan'],
                        ['name'],
                        ['email'],
                        [''],
                        [''],
                        [''],
                        ['password'],
                        ['password'],
                        ['', 'stripe'],
                        ['']
                    ];
    $data['MAX_cnt'] = 10;
    return $data;
}

function QuestionBackendForm() {
        
    global $supportedIcons;
    global $supportedText;
    global $supportedCaloriesDemo;
    global $supportedWorkoutDemo;

    $data['qContent'] = [
                            ["Your form will be created with default items including name, email, height, weight, age, gender, stress and sleep. 
                             Do you want to add customised questions to this form?"],
                            ["", "1. what is the TYPE of question you want to ask your client?"],
                            ["", ""],
                            ["2. is this question REQUIRED to be answered by the client?"],
                            ["", "3. what is the PURPOSE of your question?"],
                            ["4. are you done?","4. type the body of the question for #dynomicContent topic:"], 
                            ["5. are you done?", "5. select your list topic for #dynomicContent topic:", "5. multi-select your button options for #dynomicContent topic:"],
                            ["", "6. are you done?"],
                            [""]
                        ];
    $data['qType'] = [
                        ["button"],
                        ["message", "button"],
                        ["message", "message"],
                        ["button"],
                        ["message", "list"],
                        ["button","text"],
                        ["button", "multiButton", "multiButton"],
                        ["message", "button"],
                        ["message"]
                    ];
    
    $data['qIdx'] = [0, 1, 2, 3, 4, 5, 6, 7, 8];
    
    $data['options'] = [
                            [['fa-regular fa-thumbs-up','fa-regular fa-thumbs-down']],
                            [['fa-regular fa-circle-check'], ["fa-solid fa-list-ul", "fa-solid fa-comment-dots", "fa-solid fa-hand-pointer"]],
                            [['fa-regular fa-circle-check'], ["fa-solid fa-gears", "fa-regular fa-hand-point-right"]],
                            [['fa-regular fa-thumbs-up','fa-regular fa-thumbs-down']],
                            [['fa-regular fa-circle-check'], ["water", "calories", "workout", "sugar", "alcohol"]],
                            [['fa-regular fa-thumbs-up','fa-regular fa-thumbs-down'], [""]],
                            [['fa-regular fa-thumbs-up','fa-regular fa-thumbs-down'], ["fa-solid fa-burger", "fa-solid fa-dumbbell"],
                             $supportedIcons],
                            [['fa-regular fa-circle-check'], ['fa-regular fa-thumbs-up','fa-regular fa-thumbs-down']],
                            [['fa-regular fa-circle-check']],
                       ];
    
    $data['optionsText'] = [
                                [["YES", "NO"]],
                                [["Your campaign is added to your account."], ["list options", "write text", "multiple choice"]],
                                [["Your campaign is added to your account."], ["your form is being built ... please continue!", "please answer the prevous question"]],
                                [["YES", "NO"]],
                                [["Your campaign is added to your account."], [""]],
                                [["YES", "NO"], [""]],
                                [["YES", "NO"], [$supportedCaloriesDemo, $supportedWorkoutDemo], $supportedText],
                                [["Your campaign is added to your account."], ["YES", "NO"]],
                                [["Your campaign is added to your account."]]
                           ];
    
    $data['qRequired'] = [1, 1, 1, 1, 1, 1, 1, 1, 1];
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
                            ["6. What is your client's email address?"],
                            [""]
                        ];
    $data['qType'] = [
                            ["text"],
                            ["button"],
                            ["button"],
                            ["button"],
                            ["button"],
                            ["email"],
                            ["message"]
                     ];
    $data['qIdx'] = [0, 1, 2, 3, 4, 5, 6];
    $data['options'] = [
                            [[""]],
                            [["fa-solid fa-mars", "fa-solid fa-venus", "fa-solid fa-question"]],
                            [["fa-solid fa-arrow-up-right-dots", "fa-solid fa-hand-fist", "fa-solid fa-weight-scale"]],
                            [["fa-solid fa-brain", "fa-brands fa-nutritionix"]],
                            [["fa-solid fa-brain", "fa-brands fa-nutritionix"]],
                            [[""]],
                            [["fa-regular fa-circle-check"]],
                       ];
    $data['optionsText'] = [
                                [[""]],
                                [["male", "female", 'do not know']],
                                [["increase testosterone", "gain muscle", "lose weight"]],
                                [["ai", "nutritionist"]],
                                [["ai", "nutritionist"]],
                                [[""]],
                                [["New client is created!"]]
                           ];
    $data['qRequired'] = [0, 1, 1, 1, 1, 1, 0];
    $data['qKey'] = [
                        ['name'],
                        ['gender'],
                        ['goal'],
                        ['engine'],
                        ['engine'],
                        ['email'],
                        ['']
                    ];
    $data['MAX_cnt'] = 7;
    return $data;
}

function clientsSearchForm() {
    $data['qContent'] = [
                            ["1. search clients:"],
                            ["2. enter client's info:"]
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


function InvoiceSearchForm() {
    $data['qContent'] = [
                            ["1. search invoices by:"],
                            ["2. enter invoice info:"]
                        ];

    $data['qType']    = [
                            ["button"],
                            ["text"]
                        ];

    $data['qIdx'] = [0, 1];

    $data['options']  = [
                            [["fa-solid fa-fingerprint", "fa-regular fa-envelope", "fa-solid fa-signature"]],
                            [[""]]
                        ];

    $data['optionsText'] = [
                                [["Client's ID", "Invoice's ID", "Client's name"]],
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


function clientsInvoiceForm() {
    $data['qContent'] = [
                            ["1. Enter your client's ID:"],
                            ["2. Enter your hourly fee ($):"],
                            ["3. Enter the number of hours you provided service:"],
                            ["4. Enter payment due date:"],
                            ["4. Enter the service start date: (optional)"],
                            ["5. Enter the service end date: (optional)"],
                            [""]
                        ];

    $data['qType'] = [
                        ["text"],
                        ["text"],
                        ["text"],
                        ["text"],
                        ["text"],
                        ["text"],
                        ["message"]
                    ];

    $data['qIdx'] = [0, 1, 2, 3, 4, 5, 6];

    $data['options'] = [
                        [[""]],
                        [[""]],
                        [[""]],
                        [[""]],
                        [[""]],
                        [[""]],
                        [["fa-solid fa-file-invoice-dollar"]]
                        ];
    $data['optionsText'] = [
                            [["e. g. 12345"]],
                            [["e. g. 10$ or 10.5"]],
                            [["e. g. 5hr or 5:30"]],
                            [["MM/DD/YY"]],
                            [["MM/DD/YY"]],
                            [["MM/DD/YY"]],
                            [["Click create to review Invoice!"]]
        ];
    $data['qRequired'] = [1, 1, 1, 1, 0, 0, 1];
    $data['qKey'] = [
                        ['invoiceClientID'],
                        ['invoiceFee'],
                        ['invoiceHr'],
                        ['invoiceDue'],
                        ['invoiceStart'],
                        ['invoiceEnd'],
                        ['']
    ];
    $data['MAX_cnt'] = 7;
    return $data;
}


function PostForm() {
    $data['qContent'] = [
                            ["1. What topic is in your mind ?"],
                            ["2. What is your nutritional philosophy?"],
                            ["3. Choose the content category:"],
                            ["4. describe the picture you have in mind for your post:"],
                            [""]
                        ];

    $data['qType'] = [
                        ["text"],
                        ["list"],
                        ["list"],
                        ["text"],
                        ["message"]
                    ];

    $data['qIdx'] = [0, 1, 2, 3, 4];

    $data['options'] = [
                        [[""]],
                        [["keto diet", "carnivore diet", "vegan diet", "vegetarian diet", "balanced diet", "high protein diet", "high carbs diet"]],
                        [["health", "beauty", "nutrition", "weight loss", "fitness", "performance"]],
                        [[""]],
                        [["fa-solid fa-feather-pointed"]]
                        ];
    $data['optionsText'] = [
                            [["e. g. benefits of red meat, adding plant-based proteins into meals."]],
                            [[""]],
                            [[""]],
                            [["e. g. cooked steak on a dining table, lentils."]],
                            [["Generating your post!"]]
        ];
    $data['qRequired'] = [1, 1, 1, 1, 1];
    $data['qKey'] = [
                        [''],
                        [''],
                        [''],
                        [''],
                        ['']
    ];
    $data['MAX_cnt'] = 5;
    return $data;
}





function clientPageLoad($page) {
    global $supportedIcons;
    global $supportedText;
    
    $userId      = substr($page, 0, 6);
    if(strlen($page) > 6 + 7){
        $clientId    = substr($page, 6, 5);
        $campaignId  = substr($page, 11, 7);
    } else {
        $clientId = '';
        $campaignId  = substr($page, 6, 7);
    }


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
} elseif($page == 'finances') {
    $data      = clientsInvoiceForm();
} elseif($page == 'financesSearch') {
    $data      = InvoiceSearchForm();
} elseif($page == 'post') {
    $data      = PostForm();
}else {
    $data      = clientPageLoad($page);
}

// -------
if(strpos($landing, 'userId') == "") {
    if($landing == "") {
        $userIdURL = "0";
        $clientIdURL = "";
        $campaignIdURL = "0";
    } else { // coming from the clientPageLoad
        $userIdURL     = substr($page, 0, 6);
        if(strlen($page) > 6 + 7) {
            $clientIdURL   = substr($page, 6, 5);
            $campaignIdURL = substr($page, 11, 7);
        } else {
            $clientIdURL = '';
            $campaignIdURL = substr($page, 6, 7);
        }        
    }
} else {
    $userIdIdx        = strpos($landing, 'userId');
    $userIdLength     = 6;
    $userIdURL        = substr($landing, $userIdIdx+7, $userIdLength);


    $clientIdIdx      = strpos($landing, 'clientId');
    if($clientIdIdx != '') {
        $clientIdLength   = 5;
        $clientIdURL      = substr($landing, $clientIdIdx+9, $clientIdLength);
    } else {
        $clientIdURL = '';
    }

    $campaignIdIdx    = strpos($landing, 'campaignId');
    $campaignIdLength = 7;
    $campaignIdURL    = substr($landing, $campaignIdIdx+11, $campaignIdLength);
}

$data['userid'] = $userIdURL;
$data['clientId'] = $clientIdURL;
$data['campaignId'] = $campaignIdURL;
// -------

echo json_encode($data);


?>


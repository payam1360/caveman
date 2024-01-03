<?php

$supportedIcons = ["fa-solid fa-dumbbell","fa-solid fa-heart-pulse", 
                    "fa-regular fa-face-angry","fa-regular fa-face-meh","fa-regular fa-face-smile", 
                    "fa-regular fa-moon","fa-regular fa-face-tired", "fa-solid fa-mars","fa-solid fa-venus", 
                    "fa-regular fa-circle-check", "fa-solid fa-xmark", 
                    "fa-solid fa-signature", "fa-regular fa-registered", "fa-solid fa-user-tie", 
                    "fa-solid fa-not-equal", "fa-solid fa-list-ul", "fa-solid fa-comment-dots", "fa-solid fa-hand-pointer", 
                    "fa-solid fa-envelope", "fa-solid fa-gears", "fa-regular fa-hand-point-right", 'fa-regular fa-thumbs-up',
                    "fa-regular fa-thumbs-down", "fa-solid fa-weight-scale", "fa-solid fa-text-height", "fa-solid fa-person-cane", 
                    "fa-solid fa-water", "fa-solid fa-droplet", "fa-solid fa-droplet-slash", "fa-solid fa-arrow-up-right-dots", 
                    "fa-solid fa-brain", "fa-brands fa-nutritionix", "fa-solid fa-hand-fist", "fa-solid fa-question", 
                    "fa-solid fa-person-running", "fa-solid fa-person-walking", "fa-solid fa-bowl-food",
                    "fa-solid fa-martini-glass-citrus", "fa-solid fa-fish", "fa-solid fa-cubes-stacked"];

$supportedText = [  "gain muscle", "increase heart rate","always tired", "be less tired", "relaxed",
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

$supportedHeight =  [   "<5", "5", "5-1", "5-2", "5-3", "5-4", "5-5", "5-6", "5-6", "5-7", 
                        "5-8", "5-9", "5-10", "5-11", "6", "6-1", "6-2", "6-3", "6-4", "6-5", 
                        "6-6", "6-6", "6-7", "6-8", "6-9", "6-10", "6-11", "7>"];     


function buildClientPage($userId, $clientId, $campaignId){
    $servername  = "127.0.0.1";
    $loginname   = "root";
    $password    = "@Ssia123";
    $dbname      = "Users";
    $tablename   = "Users";
    $conn        = new mysqli($servername, $loginname, $password, $dbname);
    $landingPage = $userId . $clientId . $campaignId;   
    copy("../../template.html","../../userPages/$landingPage.html");
    $fp = fopen("../../userPages/$landingPage.html", 'a+');
    $text = "<p class='userId'>$userId</p>\n";
    fwrite($fp, $text);
    $text = "<p class='clientId'>$clientId</p>\n";
    fwrite($fp, $text);
    $text = "<p class='campaignId'>$campaignId</p>\n";
    fwrite($fp, $text);
    $text = "</body>\n";
    fwrite($fp, $text);
    $text = "</html>\n";
    fwrite($fp, $text);
    fclose($fp);
}



function saveUserDataIntoDB($Questions, $qIdx, $complete, $userId, $ip) {
       
    global $supportedAge;
    global $supportedHeight;
    global $supportedIcons;
    global $supportedText;
    global $supportedWeight;

    $servername  = "127.0.0.1";
    $loginname   = "root";
    $password    = "@Ssia123";
    $dbname      = "Users";
    $tablename   = "Users";
    $table1name  = "userAllocation";
    $flag        = false;
    // Create connection
    $conn        = new mysqli($servername, $loginname, $password, $dbname);
    // Check connection

    // question answer is empty
    $qAnswer = "";
    // get question type
    if($Questions[0]->qAnswer == 0) {
        $qType = "list";
    } else if($Questions[0]->qAnswer == 1) {
        $qType = "text";
    } else if($Questions[0]->qAnswer == 2) {
        $qType = "button";
    } else if($Questions[0]->qAnswer == 3) {
        $qType = "email";
    }
    if($qType != 'email') {
    // get question content:
        $qContent = $Questions[3]->qAnswer;
    } else {
        $qContent = 'Hey #mainNameTag, what is your email address?';
    }
    // get the clientId and campaignId
    $clientId = $Questions[0]->clientId;
    $campaignId = $Questions[0]->campaignId;
    // Keyword of the question
    $qKey = $Questions[4]->qAnswer;
    // visited field
    $visited = 0;
    // required field
    $qRequired = $Questions[2]->qAnswer;
    // get the options and options texts parsing the user text input response;
    if($qType == "button") {
        // preparing options
        $options = array();
        $optionsText = array();
        $optionsEntry = $Questions[5]->qAnswer;
        for($i = 0; $i < count($optionsEntry); $i++) {
            $options[$i] = $supportedIcons[$optionsEntry[$i]];
            $optionsText[$i] = $supportedText[$optionsEntry[$i]];
        }
        $options = implode(",", $options);
        $optionsText = implode(",", $optionsText);
        
    } else if($qType == "list") {
        $optionsEntry = $Questions[5]->qAnswer;
        $optionsText = "";
        switch($optionsEntry[0]) {
            case 0:
                $options = $supportedWeight;
                break;
            case 1:
                $options = $supportedHeight;
                break;
            case 2:
                $options = $supportedAge;
                break;
            default:
                // something else .. must go here
        }     
        $options = implode(",", $options); 
    } else if($qType == "text") {
        $options = "";
        $optionsText = "";
    } else if($qType == "email") {
        $options = "";
        $optionsText = "";
    }

    $campaignTime = date("Y-m-d");
    // first check if there is incomplete campaigns
    $sql = "SELECT used, completed 
                    FROM $table1name 
                    WHERE 
                    userId = '$userId' 
                    AND 
                    clientId = '$clientId' 
                    AND 
                    campaignId = '$campaignId';";
    $data = $conn->query($sql);
    $data = $data->fetch_assoc();
    if($data['used'] == 0 && $data['completed'] == 0 && $complete == 0) { // start
        $sql = "UPDATE $table1name SET used = '1' WHERE userId = '$userId' AND clientId = '$clientId' AND campaignId = '$campaignId';";
        $conn->query($sql);
        $sql = "INSERT INTO $tablename (userId, clientId, ip, campaignId, campaignTime, qIdx, qType, qContent, qAnswer, options, optionsText, visited, qRequired, qKey) VALUES('$userId','$clientId', '$ip', '$campaignId', '$campaignTime', '$qIdx', '$qType', '$qContent', '$qAnswer', '$options', '$optionsText', '$visited', '$qRequired', '$qKey')";
        $conn->query($sql);

    } elseif($data['used'] == 1 && $data['completed'] == 0 && $complete == 0) { // keep adding 
        $sql = "INSERT INTO $tablename (userId, clientId, ip, campaignId, campaignTime, qIdx, qType, qContent, qAnswer, options, optionsText, visited, qRequired, qKey) VALUES('$userId','$clientId', '$ip', '$campaignId', '$campaignTime', '$qIdx', '$qType', '$qContent', '$qAnswer', '$options', '$optionsText', '$visited', '$qRequired', '$qKey')";
        $conn->query($sql);

    } elseif($data['used'] == 1 && $data['completed'] == 0 && $complete == 1) { // last question to conclude the campaign
        $sql = "UPDATE $table1name SET used = '0', completed = '1' WHERE userId = '$userId' AND clientId = '$clientId' AND campaignId = '$campaignId';";
        $conn->query($sql);
        $sql = "INSERT INTO $tablename (userId, clientId, ip, campaignId, campaignTime, qIdx, qType, qContent, qAnswer, options, optionsText, visited, qRequired, qKey) VALUES('$userId','$clientId', '$ip', '$campaignId', '$campaignTime', '$qIdx', '$qType', '$qContent', '$qAnswer', '$options', '$optionsText', '$visited', '$qRequired', '$qKey')";
        $conn->query($sql);

    } elseif($data['used'] == 0 && $data['completed'] == 1 && $complete == 1) { // redesign the campaign for the given client, last entry
        $sql = "UPDATE $table1name SET used = '0', completed = '1' WHERE userId = '$userId' AND clientId = '$clientId' AND campaignId = '$campaignId';";
        $conn->query($sql);
        $sql = "UPDATE $tablename SET userId = '$userId', clientId = '$clientId', ip = '$ip', campaignId = '$campaignId', campaignTime = '$campaignTime', qType = '$qType', qContent = '$qContent', qAnswer = '$qAnswer', options = '$options', optionsText = '$optionsText', visited = '$visited', qRequired = '$qRequired', qKey = '$qKey' WHERE qIdx = '$qIdx';";
        $conn->query($sql);

    } elseif($data['used'] == 0 && $data['completed'] == 1 && $complete == 0) { // redesign the campaign for the given client
        $sql = "UPDATE $table1name SET used = '0', completed = '1' WHERE userId = '$userId' AND clientId = '$clientId' AND campaignId = '$campaignId';";
        $conn->query($sql);
        $sql = "UPDATE $tablename SET userId = '$userId', clientId = '$clientId', ip = '$ip', campaignId = '$campaignId', campaignTime = '$campaignTime', qType = '$qType', qContent = '$qContent', qAnswer = '$qAnswer', options = '$options', optionsText = '$optionsText', visited = '$visited', qRequired = '$qRequired', qKey = '$qKey' WHERE qIdx = '$qIdx';";
        $conn->query($sql);

    } elseif($data['used'] == 0 && $data['completed'] == 0 && $complete == 1) { // the use only designed 1 question for his client
        $sql = "UPDATE $table1name SET used = '0', completed = '1' WHERE userId = '$userId' AND clientId = '$clientId' AND campaignId = '$campaignId';";
        $conn->query($sql);
        $sql = "INSERT INTO $tablename (userId, clientId, ip, campaignId, campaignTime, qIdx, qType, qContent, qAnswer, options, optionsText, visited, qRequired, qKey) VALUES('$userId','$clientId', '$ip', '$campaignId', '$campaignTime', '$qIdx', '$qType', '$qContent', '$qAnswer', '$options', '$optionsText', '$visited', '$qRequired', '$qKey')";
        $conn->query($sql);

    } else {
        // undefined ... we should not be here technically.
    }
    $Info['clientId']   = $clientId;
    $Info['campaignId'] = $campaignId;
    $conn->close();
    return $Info;
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
// get the user ID
$userId         = $userdata->data[0]->userId;
// check the question type selected by the user (nutritionist)
if($userdata->data[0]->qAnswer == '') {
    $data['MAX_cnt'] = 0;
} elseif($userdata->data[0]->qAnswer == '0') {
    $data['MAX_cnt'] = 7;
} elseif($userdata->data[0]->qAnswer == '1') {
    $data['MAX_cnt'] = 6;
} elseif($userdata->data[0]->qAnswer == '2') {
    $data['MAX_cnt'] = 7;
} elseif($userdata->data[0]->qAnswer == '3') {
    $data['MAX_cnt'] = 4;
}

if($userdata->data[0]->qAnswer == '') {
    $data['status'] = 0;
} elseif($userdata->data[0]->qAnswer == '0' && $userdata->counter == 3) {
    $data['status'] = 03; 
} elseif($userdata->data[0]->qAnswer == '0' && $userdata->counter == 4) {
    $data['status'] = 04;    
} elseif($userdata->data[0]->qAnswer == '0' && $userdata->counter == 5) {
    $data['status'] = 05;
} elseif($userdata->data[0]->qAnswer == '0' && $userdata->counter == 6) {
    $data['status'] = 06;
} elseif($userdata->data[0]->qAnswer == '1' && $userdata->counter == 3) {
    $data['status'] = 13; 
} elseif($userdata->data[0]->qAnswer == '1' && $userdata->counter == 4) {
    $data['status'] = 14; 
} elseif($userdata->data[0]->qAnswer == '1' && $userdata->counter == 5) {
    $data['status'] = 15;
} elseif($userdata->data[0]->qAnswer == '3' && $userdata->counter == 3) {
    $data['status'] = 33; 
} elseif($userdata->data[0]->qAnswer == '3' && $userdata->counter == 4) {
    $data['status'] = 34;  
} elseif($userdata->data[0]->qAnswer == '2' && $userdata->counter == 3) {
    $data['status'] = 23; 
} elseif($userdata->data[0]->qAnswer == '2' && $userdata->counter == 4) {
    $data['status'] = 24;  
} elseif($userdata->data[0]->qAnswer == '2' && $userdata->counter == 5) {
    $data['status'] = 25;
} elseif($userdata->data[0]->qAnswer == '2' && $userdata->counter == 6) {
    $data['status'] = 26;
} else {
    $data['status'] = 1;
}

if($data['MAX_cnt']  == $userdata->counter && $userdata->data[$userdata->counter-1]->qAnswer == 1 ) {
    $data['status'] = 11; // keep asking
    saveUserDataIntoDB($userdata->data, $userdata->qIdx, 0, $userId, $ip);
} elseif($data['MAX_cnt'] == $userdata->counter && $userdata->data[$userdata->counter-1]->qAnswer == 0) {
    $data['status'] = 12; // End the form builder
    $Info = saveUserDataIntoDB($userdata->data, $userdata->qIdx, 1, $userId, $ip);
    buildClientPage($userId, $Info['clientId'], $Info['campaignId']);
}
echo json_encode($data);

?>


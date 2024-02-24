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

$supportedText = [  "gain muscle", "increase heart rate","high stress", "medium stress", "low stress",
                    "well rested", "get less sleep", "male", "female", "great", "bad", 
                    "sign up", "register", "new user", "not equal", "list", "text", 
                    "multiple choice", "email", "work process", "proceed", "ok", "not ok",
                    "lose weight", "height", "age",  "drink lot of water", "drink enough water", 
                    "drink less water", "increase testosterone", "ai", "nutritionist", "intense workout",
                    "do not know", "cardio", "walking", "enough calories", "alcohol", "omega 3", "sugar"];


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

// this is to make campaign raw page only.
// the landing page to clients will be a copy/paste of this page with clientId attached.
function buildCampaignPage($userId, $campaignId){
    $servername  = "127.0.0.1";
    $loginname   = "root";
    $password    = "@Ssia123";
    $dbname      = "Users";
    $tablename   = "Users";
    $conn        = new mysqli($servername, $loginname, $password, $dbname);
    $landingPage = $userId . $campaignId;   
    copy("../../template.html","../../userPages/$landingPage.html");
    $fp = fopen("../../userPages/$landingPage.html", 'a+');
    $text = "<p class='userId' style='display: none'>$userId</p>\n"; 
    fwrite($fp, $text);
    $text = "<p class='campaignId' style='display: none'>$campaignId</p>\n";
    fwrite($fp, $text);
    $text = "<p class='clientId' style='display: none'></p>\n";
    fwrite($fp, $text);
    $text = "</body>\n";
    fwrite($fp, $text);
    $text = "</html>\n";
    fwrite($fp, $text);
    fclose($fp);
}

function createDefaultForm($userId, $campaignId){

    $numDefaultQ = 8;
    global $supportedAge;
    global $supportedHeight;
    global $supportedWeight;


    $servername  = "127.0.0.1";
    $loginname   = "root";
    $password    = "@Ssia123";
    $dbname      = "Users";
    $tablename   = "Users";
    $conn        = new mysqli($servername, $loginname, $password, $dbname);
    for($kk = 0; $kk < $numDefaultQ; $kk++) {
        switch($kk){
            case 0:
                $qType = 'text';
                $qContent = '1. Hey there, what is your name?';
                $options = "";
                $optionsText = "";
                $qKey = 'name';
            break;
            case 1:
                $qType = 'button';
                $qContent = '2. Hi #mainNameTag, what is your nutritional goal?';
                $options = "fa-solid fa-weight-scale,fa-solid fa-dumbbell,fa-solid fa-heart-pulse";
                $optionsText = "lose weight,gain muscles,be less tired";
                $qKey = 'goal';
            break;
            case 2:
                $qType = 'list';
                $qContent = '3. How much do you weigh? (lb)';
                $options = implode(",", $supportedWeight);
                $optionsText = "";
                $qKey = 'weight';
            break;
            case 3:
                $qType = 'list';
                $qContent = '4. How tall are you? (ft-in)';
                $options = implode(",", $supportedHeight);
                $optionsText = "";
                $qKey = 'height';
            break;
            case 4:
                $qType = 'button';
                $qContent = '5. What is your age?';
                $options = implode(",", $supportedAge);
                $optionsText = "";
                $qKey = 'age';
            break;
            case 5:
                $qType = 'button';
                $qContent = '6. How is your stress level?';
                $options = "fa-regular fa-face-angry,fa-regular fa-face-meh,fa-regular fa-face-smile";
                $optionsText = "high stress,medium stress,low stress";
                $qKey = 'stress';
            break;
            case 6:
                $qType = 'button';
                $qContent = '7. How is your sleep?';
                $options = "fa-regular fa-moon,fa-regular fa-face-tired";
                $optionsText = "well rested,get less sleep";
                $qKey = 'sleep';
            break;
            case 7:
                $qType = 'button';
                $qContent = '8. And finally, you identify as:';
                $options = "fa-solid fa-mars,fa-solid fa-venus";
                $optionsText = "male,female";
                $qKey = 'gender';
            break;
        }
        $sql         = "INSERT INTO $tablename (userId, clientId, ip, campaignId, qIdx, qType, qContent, qAnswer, options, optionsText, visited, qRequired, qKey) VALUES('$userId','0', '', '$campaignId', '$kk', '$qType', '$qContent', '', '$options', '$optionsText', '0', '1', '$qKey')";
        $conn->query($sql);
    }

}


function setCampaignStartComplete($campaignIdSource, $userId) {
    $servername  = "127.0.0.1";
    $loginname   = "root";
    $password    = "@Ssia123";
    $dbname      = "Users";
    $table1name  = "userAllocation";
    $conn        = new mysqli($servername, $loginname, $password, $dbname);
    $campaignTimeStamp = date('Y-m-d H:i:s');
    $sql = "UPDATE $table1name SET completed = '1', campaignTimeStamp = '$campaignTimeStamp' WHERE userId = '$userId' AND campaignIdSource = '$campaignIdSource';";
    $conn->query($sql);
    buildCampaignPage($userId, $campaignIdSource);
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
    // get the campaignId
    $campaignIdSource  = $Questions[0]->campaignId;
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
    // first check if there is incomplete campaigns
    $sql = "SELECT used, completed 
                    FROM $table1name 
                    WHERE 
                    userId = '$userId' 
                    AND 
                    campaignIdSource = '$campaignIdSource';";
    $data = $conn->query($sql);
    $data = $data->fetch_assoc();
    if($data['used'] == 0 && $data['completed'] == 0 && $complete == 0) { // start
        $campaignTimeStamp = date('Y-m-d H:i:s');
        $sql = "UPDATE $table1name SET used = '1', campaignTimeStamp = '$campaignTimeStamp' WHERE userId = '$userId' AND campaignIdSource = '$campaignIdSource';";
        $conn->query($sql);
        $sql = "INSERT INTO $tablename (userId, clientId, ip, campaignId, qIdx, qType, qContent, qAnswer, options, optionsText, visited, qRequired, qKey) VALUES('$userId','0', '$ip', '$campaignIdSource', '$qIdx', '$qType', '$qContent', '$qAnswer', '$options', '$optionsText', '$visited', '$qRequired', '$qKey')";
        $conn->query($sql);

    } elseif($data['used'] == 1 && $data['completed'] == 0 && $complete == 0) { // keep adding 
        $sql = "INSERT INTO $tablename (userId, clientId, ip, campaignId, qIdx, qType, qContent, qAnswer, options, optionsText, visited, qRequired, qKey) VALUES('$userId','0', '$ip', '$campaignIdSource', '$qIdx', '$qType', '$qContent', '$qAnswer', '$options', '$optionsText', '$visited', '$qRequired', '$qKey')";
        $conn->query($sql);

    } elseif($data['used'] == 1 && $data['completed'] == 0 && $complete == 1) { // last question to conclude the campaign
        $sql = "UPDATE $table1name SET used = '0', completed = '1' WHERE userId = '$userId' AND campaignIdSource = '$campaignIdSource';";
        $conn->query($sql);
        $sql = "INSERT INTO $tablename (userId, clientId, ip, campaignId, qIdx, qType, qContent, qAnswer, options, optionsText, visited, qRequired, qKey) VALUES('$userId','0', '$ip', '$campaignIdSource', '$qIdx', '$qType', '$qContent', '$qAnswer', '$options', '$optionsText', '$visited', '$qRequired', '$qKey')";
        $conn->query($sql);

    } elseif($data['used'] == 0 && $data['completed'] == 1 && $complete == 1) { // redesign the campaign for the given client, last entry
        $sql = "UPDATE $table1name SET used = '0', completed = '1' WHERE userId = '$userId' AND campaignIdSource = '$campaignIdSource';";
        $conn->query($sql);
        $sql = "UPDATE $tablename SET userId = '$userId', clientId = '0', ip = '$ip', campaignIdSource = '$campaignIdSource', qType = '$qType', qContent = '$qContent', qAnswer = '$qAnswer', options = '$options', optionsText = '$optionsText', visited = '$visited', qRequired = '$qRequired', qKey = '$qKey' WHERE qIdx = '$qIdx';";
        $conn->query($sql);

    } elseif($data['used'] == 0 && $data['completed'] == 1 && $complete == 0) { // redesign the campaign for the given client
        $sql = "UPDATE $table1name SET used = '0', completed = '1' WHERE userId = '$userId' AND campaignIdSource = '$campaignIdSource';";
        $conn->query($sql);
        $sql = "UPDATE $tablename SET userId = '$userId', clientId = '0', ip = '$ip', campaignIdSource = '$campaignIdSource', qType = '$qType', qContent = '$qContent', qAnswer = '$qAnswer', options = '$options', optionsText = '$optionsText', visited = '$visited', qRequired = '$qRequired', qKey = '$qKey' WHERE qIdx = '$qIdx';";
        $conn->query($sql);

    } elseif($data['used'] == 0 && $data['completed'] == 0 && $complete == 1) { // the use only designed 1 question for his client
        $sql = "UPDATE $table1name SET used = '0', completed = '1' WHERE userId = '$userId' AND campaignIdSource = '$campaignIdSource';";
        $conn->query($sql);
        $sql = "INSERT INTO $tablename (userId, clientId, ip, campaignId, qIdx, qType, qContent, qAnswer, options, optionsText, visited, qRequired, qKey) VALUES('$userId','0', '$ip', '$campaignIdSource', '$qIdx', '$qType', '$qContent', '$qAnswer', '$options', '$optionsText', '$visited', '$qRequired', '$qKey')";
        $conn->query($sql);

    } else {
        // undefined ... we should not be here technically.
    }
    $Info['campaignIdSource'] = $campaignIdSource;
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
// get the user ID and campaignId and create default form
$userId         = $userdata->data[0]->userId;
$campaignId     = $userdata->data[0]->campaignId;
if($userdata->counter == 1) {
    createDefaultForm($userId, $campaignId);
}
if($userdata->data[0]->qAnswer == '0') {
    // will be depending on the later choice
} elseif($userdata->data[0]->qAnswer == '1') {
    $data['MAX_cnt'] = 3;
}

// check the question type selected by the user (nutritionist)
if($userdata->data[1]->qAnswer == '' && $userdata->data[0]->qAnswer == '0') {
    $data['MAX_cnt'] = 7; // we still don't know the user's choice in type of Q being added
} elseif($userdata->data[1]->qAnswer == '0') {
    $data['MAX_cnt'] = 7;
} elseif($userdata->data[1]->qAnswer == '1') {
    $data['MAX_cnt'] = 6;
} elseif($userdata->data[1]->qAnswer == '2') {
    $data['MAX_cnt'] = 7;
} elseif($userdata->data[1]->qAnswer == '3') {
    $data['MAX_cnt'] = 4;
}


if($userdata->data[0]->qAnswer == '0') {

if($userdata->data[1]->qAnswer == '') {
    $data['status'] = 0;
} elseif($userdata->data[1]->qAnswer == '0' && $userdata->counter == 3) {
    $data['status'] = 03; 
} elseif($userdata->data[1]->qAnswer == '0' && $userdata->counter == 4) {
    $data['status'] = 04;    
} elseif($userdata->data[1]->qAnswer == '0' && $userdata->counter == 5) {
    $data['status'] = 05;
} elseif($userdata->data[1]->qAnswer == '0' && $userdata->counter == 6) {
    $data['status'] = 06;
} elseif($userdata->data[1]->qAnswer == '1' && $userdata->counter == 3) {
    $data['status'] = 13; 
} elseif($userdata->data[1]->qAnswer == '1' && $userdata->counter == 4) {
    $data['status'] = 14; 
} elseif($userdata->data[1]->qAnswer == '1' && $userdata->counter == 5) {
    $data['status'] = 15;
} elseif($userdata->data[1]->qAnswer == '3' && $userdata->counter == 3) {
    $data['status'] = 33; 
} elseif($userdata->data[1]->qAnswer == '3' && $userdata->counter == 4) {
    $data['status'] = 34;  
} elseif($userdata->data[1]->qAnswer == '2' && $userdata->counter == 3) {
    $data['status'] = 23; 
} elseif($userdata->data[1]->qAnswer == '2' && $userdata->counter == 4) {
    $data['status'] = 24;  
} elseif($userdata->data[1]->qAnswer == '2' && $userdata->counter == 5) {
    $data['status'] = 25;
} elseif($userdata->data[1]->qAnswer == '2' && $userdata->counter == 6) {
    $data['status'] = 26;
} else {
    $data['status'] = 1;
}
} else {
    $data['status'] = 0;
}

if($userdata->data[0]->qAnswer == '1'){
    setCampaignStartComplete($campaignId, $userId);
} elseif($data['MAX_cnt']  == $userdata->counter && $userdata->data[$userdata->counter-1]->qAnswer == 1 ) {
    $data['status'] = 11; // keep asking
    saveUserDataIntoDB($userdata->data, $userdata->qIdx, 0, $userId, $ip);
} elseif($data['MAX_cnt'] == $userdata->counter && $userdata->data[$userdata->counter-1]->qAnswer == 0) {
    $data['status'] = 12; // End the form builder
    $Info = saveUserDataIntoDB($userdata->data, $userdata->qIdx, 1, $userId, $ip);
    buildCampaignPage($userId, $Info['campaignIdSource']);
}
echo json_encode($data);

?>


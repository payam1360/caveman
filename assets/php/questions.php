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
$supportedWorkout =  [  'never', '1hr a week', '3hrs a week', '5hrs a week', '7hrs a week'];

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

    $numDefaultQ = 9;
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
                $qContent = '2. Hi #dynomicContent, what is your nutritional / target weight goal?';
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
                $qType = 'list';
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
                $qContent = '8. And you identify as:';
                $options = "fa-solid fa-mars,fa-solid fa-venus";
                $optionsText = "male,female";
                $qKey = 'gender';
            break;
            case 8:
                $qType = 'email';
                $qContent = '9. #dynomicContent, will you share your email with me?';
                $options = "";
                $optionsText = "";
                $qKey = 'email';
            break;
        }
        $sql         = "INSERT INTO $tablename (userId, clientId, ip, campaignId, qIdx, qType, qContent, qAnswer, options, optionsText, visited, qRequired, qKey) VALUES('$userId','', '', '$campaignId', '$kk', '$qType', '$qContent', '', '$options', '$optionsText', '0', '1', '$qKey')";
        $conn->query($sql);
    }

}


function setCampaignStartComplete($campaignIdSource, $userId) {
    $servername  = "127.0.0.1";
    $loginname   = "root";
    $password    = "@Ssia123";
    $dbname      = "Users";
    $table1name  = "campaignAlloc";
    $conn        = new mysqli($servername, $loginname, $password, $dbname);
    $campaignTimeStamp = date('Y-m-d H:i:s');
    $sql = "UPDATE $table1name SET completed = '1', campaignTimeStamp = '$campaignTimeStamp' WHERE userId = '$userId' AND campaignIdSource = '$campaignIdSource';";
    $conn->query($sql);
    buildCampaignPage($userId, $campaignIdSource);
}


function saveUserDataIntoDB($Questions, $qIdx, $complete, $userId, $ip) {
       
    global $supportedCalories;
    global $supportedWorkout;
    global $supportedIcons;
    global $supportedText;
    

    $servername  = "127.0.0.1";
    $loginname   = "root";
    $password    = "@Ssia123";
    $dbname      = "Users";
    $tablename   = "Users";
    $table1name  = "userAllocation";
    $table2name  = "campaignAlloc";    
    $flag        = false;
    // Create connection
    $conn        = new mysqli($servername, $loginname, $password, $dbname);
    // Check connection

    // question answer is empty
    $qAnswer = "";
    // get question type
    if($Questions[1]->qAnswer == 0) {
        $qType = "list";
    } else if($Questions[1]->qAnswer == 1) {
        $qType = "text";
    } else if($Questions[1]->qAnswer == 2) {
        $qType = "button";
    } 
    // Keyword of the question
    $qKey = $Questions[4]->qAnswer;
    // get question content:
    $qContent = $Questions[5]->qAnswer;
    // get the campaignId
    $campaignIdSource  = $Questions[0]->campaignId;    
    // visited field
    $visited = 0;
    // required field
    $qRequired = $Questions[3]->qAnswer;
    // get the options and options texts parsing the user text input response;
    if($qType == "button") {
        // preparing options
        $options = array();
        $optionsText = array();
        $optionsEntry = $Questions[6]->qAnswer;
        for($i = 0; $i < count($optionsEntry); $i++) {
            $options[$i] = $supportedIcons[$optionsEntry[$i]];
            $optionsText[$i] = $supportedText[$optionsEntry[$i]];
        }
        $options = implode(",", $options);
        $optionsText = implode(",", $optionsText);
        
    } else if($qType == "list") {
        $optionsEntry = $Questions[6]->qAnswer;
        $optionsText = "";
        switch($optionsEntry[0]) {
            case 0:
                $options = $supportedCalories;
                break;
            case 1:
                $options = $supportedWorkout;
                break;
            default:
                // something else .. must go here
        }     
        $options = implode(",", $options); 
    } else if($qType == "text") {
        $options = "";
        $optionsText = "";
    } 
    // first check if there is incomplete campaigns
    $sql = "SELECT used, completed FROM $table2name WHERE userId = '$userId' AND campaignIdSource = '$campaignIdSource';";
    $data = $conn->query($sql);
    $data = $data->fetch_assoc();
    if($data['used'] == 0 && $data['completed'] == 0 && $complete == 0) { // start
        $campaignTimeStamp = date('Y-m-d H:i:s');
        $sql = "UPDATE $table2name SET used = '1', campaignTimeStamp = '$campaignTimeStamp' WHERE userId = '$userId' AND campaignIdSource = '$campaignIdSource';";
        $conn->query($sql);
        $sql = "INSERT INTO $tablename (userId, clientId, ip, campaignId, qIdx, qType, qContent, qAnswer, options, optionsText, visited, qRequired, qKey) VALUES('$userId','', '$ip', '$campaignIdSource', '$qIdx', '$qType', '$qContent', '$qAnswer', '$options', '$optionsText', '$visited', '$qRequired', '$qKey')";
        $conn->query($sql);

    } elseif($data['used'] == 1 && $data['completed'] == 0 && $complete == 0) { // keep adding 
        $sql = "INSERT INTO $tablename (userId, clientId, ip, campaignId, qIdx, qType, qContent, qAnswer, options, optionsText, visited, qRequired, qKey) VALUES('$userId','', '$ip', '$campaignIdSource', '$qIdx', '$qType', '$qContent', '$qAnswer', '$options', '$optionsText', '$visited', '$qRequired', '$qKey')";
        $conn->query($sql);

    } elseif($data['used'] == 1 && $data['completed'] == 0 && $complete == 1) { // last question to conclude the campaign
        $sql = "UPDATE $table2name SET used = '0', completed = '1' WHERE userId = '$userId' AND campaignIdSource = '$campaignIdSource';";
        $conn->query($sql);
        $sql = "INSERT INTO $tablename (userId, clientId, ip, campaignId, qIdx, qType, qContent, qAnswer, options, optionsText, visited, qRequired, qKey) VALUES('$userId','', '$ip', '$campaignIdSource', '$qIdx', '$qType', '$qContent', '$qAnswer', '$options', '$optionsText', '$visited', '$qRequired', '$qKey')";
        $conn->query($sql);

    } elseif($data['used'] == 0 && $data['completed'] == 1 && $complete == 1) { // redesign the campaign for the given client, last entry
        $sql = "UPDATE $table2name SET used = '0', completed = '1' WHERE userId = '$userId' AND campaignIdSource = '$campaignIdSource';";
        $conn->query($sql);
        $sql = "UPDATE $tablename SET userId = '$userId', clientId = '', ip = '$ip', campaignId = '$campaignIdSource', qType = '$qType', qContent = '$qContent', qAnswer = '$qAnswer', options = '$options', optionsText = '$optionsText', visited = '$visited', qRequired = '$qRequired', qKey = '$qKey' WHERE qIdx = '$qIdx';";
        $conn->query($sql);

    } elseif($data['used'] == 0 && $data['completed'] == 1 && $complete == 0) { // redesign the campaign for the given client
        $sql = "UPDATE $table2name SET used = '0', completed = '1' WHERE userId = '$userId' AND campaignIdSource = '$campaignIdSource';";
        $conn->query($sql);
        $sql = "UPDATE $tablename SET userId = '$userId', clientId = '', ip = '$ip', campaignId = '$campaignIdSource', qType = '$qType', qContent = '$qContent', qAnswer = '$qAnswer', options = '$options', optionsText = '$optionsText', visited = '$visited', qRequired = '$qRequired', qKey = '$qKey' WHERE qIdx = '$qIdx';";
        $conn->query($sql);

    } elseif($data['used'] == 0 && $data['completed'] == 0 && $complete == 1) { // the user only designed 1 question for his client
        $campaignTimeStamp = date('Y-m-d H:i:s');
        $sql = "UPDATE $table2name SET used = '0', campaignTimeStamp = '$campaignTimeStamp', completed = '1' WHERE userId = '$userId' AND campaignIdSource = '$campaignIdSource';";
        $conn->query($sql);
        $sql = "INSERT INTO $tablename (userId, clientId, ip, campaignId, qIdx, qType, qContent, qAnswer, options, optionsText, visited, qRequired, qKey) VALUES('$userId','', '$ip', '$campaignIdSource', '$qIdx', '$qType', '$qContent', '$qAnswer', '$options', '$optionsText', '$visited', '$qRequired', '$qKey')";
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
    $data['MAX_cnt'] = 8; // we still don't know the user's choice in type of Q being added
} elseif($userdata->data[1]->qAnswer == '0') {
    $data['MAX_cnt'] = 8;
} elseif($userdata->data[1]->qAnswer == '1') {
    $data['MAX_cnt'] = 7;
} elseif($userdata->data[1]->qAnswer == '2') {
    $data['MAX_cnt'] = 8;
} elseif($userdata->data[1]->qAnswer == '3') {
    $data['MAX_cnt'] = 5;
}

if($userdata->data[0]->qAnswer == '0' || $userdata->data[0]->qAnswer == '') {

    if($userdata->data[1]->qAnswer == '') {
        $data['status'] = 01;
    } elseif($userdata->counter == 3) {
        $data['status'] = 03; 
    } elseif($userdata->data[1]->qAnswer == '0' && $userdata->counter == 4) {
        $data['status'] = 04; 
    } elseif($userdata->data[1]->qAnswer == '0' && $userdata->counter == 5) {
        $data['status'] = 05;    
    } elseif($userdata->data[1]->qAnswer == '0' && $userdata->counter == 6) {
        $data['status'] = 06;
    } elseif($userdata->data[1]->qAnswer == '0' && $userdata->counter == 7) {
        $data['status'] = 07;
    } elseif($userdata->data[1]->qAnswer == '1' && $userdata->counter == 4) {
        $data['status'] = 14; 
    } elseif($userdata->data[1]->qAnswer == '1' && $userdata->counter == 5) {
        $data['status'] = 15; 
    } elseif($userdata->data[1]->qAnswer == '1' && $userdata->counter == 6) {
        $data['status'] = 16;
    } elseif($userdata->data[1]->qAnswer == '3' && $userdata->counter == 4) {
        $data['status'] = 34; 
    } elseif($userdata->data[1]->qAnswer == '3' && $userdata->counter == 5) {
        $data['status'] = 35;  
    } elseif($userdata->data[1]->qAnswer == '2' && $userdata->counter == 4) {
        $data['status'] = 24; 
    } elseif($userdata->data[1]->qAnswer == '2' && $userdata->counter == 5) {
        $data['status'] = 25;  
    } elseif($userdata->data[1]->qAnswer == '2' && $userdata->counter == 6) {
        $data['status'] = 26;
    } elseif($userdata->data[1]->qAnswer == '2' && $userdata->counter == 7) {
        $data['status'] = 27;
    } else {
        $data['status'] = 100;
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


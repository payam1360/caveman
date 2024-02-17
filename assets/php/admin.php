<?php


function getCampaignIdSource($userId){
    $servername  = "127.0.0.1";
    $loginname   = "root";
    $password    = "@Ssia123";
    $dbname      = "Users";
    $tablename  = "userAllocation";
    $conn        = new mysqli($servername, $loginname, $password, $dbname);
    $sql         = "SELECT campaignIdSource, campaignTimeStamp, completed FROM $tablename WHERE userId = '$userId';";
    $data        = $conn->query($sql);
    $campaignIdSource = array();
    $campaignTimeStamp = array();
    $completed = array();
    
    while($campaign = $data->fetch_assoc()){
        array_push($campaignIdSource, $campaign['campaignIdSource']);
        array_push($campaignTimeStamp, $campaign['campaignTimeStamp']);
        array_push($completed, $campaign['completed']);
    }
    $data->campaignIdSource = $campaignIdSource;
    $data->campaignTimeStamp = $campaignTimeStamp;
    $data->completed = $completed;
    return($data);
}

// functions go here
session_start();
$userName   = $_SESSION['userName'];
$userId     = $_SESSION['userId'];
if(isset($userName) && isset($userId)) {
    $userName   = $_SESSION['userName'];
    $userId     = $_SESSION['userId'];
    $data['status'] = 0;
} else {
    $data['status'] = 1;
    $userName   = [];
    $userId     = [];
}

$userdata   = $_POST['address'];
if(strpos($userdata, 'userId') == "") {
    $userIdURL = "0";
    $clientIdURL = "0";
    $campaignIdURL = "0";
} else {

    $userIdIdx        = strpos($userdata, 'userId');
    $userIdLength     = 6;
    $userIdURL        = substr($userdata, $userIdIdx+7, $userIdLength);


    $clientIdIdx      = strpos($userdata, 'clientId');
    if($clientIdIdx != '') {
        $clientIdLength   = 5;
        $clientIdURL      = substr($userdata, $clientIdIdx+9, $clientIdLength);
    } else {
        $clientIdURL = '0';
    }

    $campaignIdIdx    = strpos($userdata, 'campaignId');
    $campaignIdLength = 7;
    $campaignIdURL    = substr($userdata, $campaignIdIdx+11, $campaignIdLength);
}

$data['userid'] = $userId;
$data['username'] = $userName;
$data['clientId'] = $clientIdURL;
$data['campaignId'] = $campaignIdURL;
$data['campaignSourceInfo'] = getCampaignIdSource($userId);
echo json_encode($data);


?>

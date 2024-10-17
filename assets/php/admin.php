<?php


function getCampaignIdSource($userId){
    $servername  = "127.0.0.1";
    $loginname   = "root";
    $password    = "@Ssia123";
    $dbname      = "Users";
    $table1name  = "userAllocation";
    $table2name  = "campaignAlloc";    
    $conn        = new mysqli($servername, $loginname, $password, $dbname);
    $sql         = "SELECT name FROM $table1name WHERE userId = '$userId';";
    $data        = $conn->query($sql);
    $sql2        = "SELECT campaignIdSource, campaignTimeStamp, completed FROM $table2name WHERE userId = '$userId';";
    $data2       = $conn->query($sql2);
    $campaignIdSource  = array();
    $campaignTimeStamp = array();
    $completed         = array();
    $name              = array();
    while($campaign2 = $data2->fetch_assoc()){
        array_push($campaignIdSource, $campaign2['campaignIdSource']);
        array_push($campaignTimeStamp, $campaign2['campaignTimeStamp']);
        array_push($completed, $campaign2['completed']);
    }
    while($campaign = $data->fetch_assoc()){
        array_push($name, $campaign['name']);
    }
    $data->campaignIdSource = $campaignIdSource;
    $data->campaignTimeStamp = $campaignTimeStamp;
    $data->completed = $completed;
    $data->name = $name;
    return($data);
}

// functions go here
session_start();
$userName   = [];
$userId     = [];
if(isset($_SESSION['userName']) && isset($_SESSION['userId'])) {
    $userName   = $_SESSION['userName'];
    $userId     = $_SESSION['userId'];
    $data['status'] = 0;
} else {
    $data['status'] = 1;
}

if($data['status'] == 0) {
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
}
echo json_encode($data);


?>

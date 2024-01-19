<?php

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
    $clientIdIdx      = strpos($userdata, 'clientId');
    $campaignIdIdx    = strpos($userdata, 'campaignId');

    $userIdLength     = 6;
    $clientIdLength   = 5;
    $campaignIdLength = 7;

    $userIdURL        = substr($userdata, $userIdIdx+7, $userIdLength);
    $clientIdURL      = substr($userdata, $clientIdIdx+9, $clientIdLength);
    $campaignIdURL    = substr($userdata, $campaignIdIdx+11, $campaignIdLength);
}

$data['userid'] = $userId;
$data['username'] = $userName;
$data['clientId'] = $clientIdURL;
$data['campaignId'] = $campaignIdURL;

echo json_encode($data);


?>

<?php

// functions go here

$userdata         = $_POST['address'];
$userIdIdx        = strpos($userdata, 'userId');
$clientIdIdx      = strpos($userdata, 'clientId');
$campaignIdIdx    = strpos($userdata, 'campaignId');

$userIdLength     = 6;
$clientIdLength   = 5;
$campaignIdLength = 7;

$userIdURL        = substr($userdata, $userIdIdx+7, $userIdLength);
$clientIdURL      = substr($userdata, $clientIdIdx+9, $clientIdLength);
$campaignIdURL    = substr($userdata, $campaignIdIdx+11, $campaignIdLength);


session_start();
$userName = $_SESSION['userName'];
$userId = $_SESSION['userId'];
$data['username'] = $userName;
$data['userid'] = $userId;
$data['clientId'] = $clientIdURL;
$data['campaignId'] = $campaignIdURL;

echo json_encode($data);


?>

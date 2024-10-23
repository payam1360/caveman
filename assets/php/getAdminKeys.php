<?php

function getStripePublicToken($conn){
    $tablename   = "admin";
    $sql         = "SELECT stripePublicToken FROM $tablename;";
    $db_out      = $conn->query($sql);
    $stripePublicToken = $db_out->fetch_assoc();
    $stripePublicToken = $stripePublicToken['stripePublicToken'];
    return $stripePublicToken;
}
function getPexelToken($conn){
    $tablename   = "admin";
    $sql         = "SELECT pexelToken FROM $tablename;";
    $db_out      = $conn->query($sql);
    $pexelToken = $db_out->fetch_assoc();
    $pexelToken = $pexelToken['pexelToken'];
    return $pexelToken;
}
function getTelegramToken($conn){
    $tablename   = "admin";
    $sql         = "SELECT telegramToken FROM $tablename;";
    $db_out      = $conn->query($sql);
    $telegramToken = $db_out->fetch_assoc();
    $telegramToken = $telegramToken['telegramToken'];
    return $telegramToken;
}

$userInfo      = json_decode($_POST['userInfo']);
// server connect
$servername  = "127.0.0.1";
$loginname   = "root";
$password    = "@Ssia123";
$dbname      = "Users";
$conn        = new mysqli($servername, $loginname, $password, $dbname);
// --------
if($userInfo->flag == 'stripeToken') {
    $response = getStripePublicToken($conn);
} elseif($userInfo->flag == 'pexelToken'){
    $response = getPexelToken($conn);
} elseif($userInfo->flag == 'telegramToken'){
    $response = getTelegramToken($conn);
} 
$conn->close();
echo json_encode($response);

?>
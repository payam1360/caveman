<?php


function deleteWebhook($botToken) {
    $url = 'https://api.telegram.org/bot' . $botToken . '/deleteWebhook';
    $response = file_get_contents($url);
    return json_decode($response, true);
}

function setWebhook($botToken, $webhookUrl) {
    $url = 'https://api.telegram.org/bot' . $botToken . '/setWebhook?url=' . urlencode($webhookUrl);
    $response = file_get_contents($url);
    return json_decode($response, true);
}

function getWebhookInfo($botToken) {
    $url = 'https://api.telegram.org/bot' . $botToken . '/getWebhookInfo';
    $response = file_get_contents($url);
    return json_decode($response, true);
}



function sendTelegramMessage($conn, $userdata){

    $accessToken = getTelegramTokenFromDb();
    deleteWebhook($accessToken);
    $updates  = getTelegramUpdates($accessToken);
    $response = sendTelegram($conn, $userdata, $accessToken, $updates); 
    // setup webhook again
    $webhookUrl = 'https://barely-huge-lizard.ngrok-free.app/assets/php/telegramWebHook.php';
    setWebhook($accessToken, $webhookUrl);
    return $response;
}

function getTelegramUpdates($botToken) {
    $url = 'https://api.telegram.org/bot' . $botToken . '/getUpdates';
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    $data = json_decode($response, true);
    curl_close($ch);
    return $data; // This will show you the chat ID among other details
}

function getTelegramTokenFromDb(){
    $servername  = "127.0.0.1";
    $loginname   = "root";
    $password    = "@Ssia123";
    $dbname      = "Users";
    $conn        = new mysqli($servername, $loginname, $password, $dbname);
    $tablename   = 'admin';
    $sql         = "SELECT telegramToken FROM $tablename;";
    $dbOut       = $conn->query($sql);
    $dbOut       = $dbOut->fetch_assoc();
    $conn->close();
    return $dbOut['telegramToken'];
}


function sendTelegram($conn, $userdata, $botToken, $updates) {
    
    $userId   = $userdata->userId;
    $telegramUserName = $userdata->clientTelegramUserName;
    $chatId = getTelegramChatIdfromDb($conn, $userId, $telegramUserName);
    $url = 'https://api.telegram.org/bot' . $botToken . '/sendMessage';
    $data = [
        'chat_id' => $chatId,
        'text' => $userdata->message
    ];
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
    $response = curl_exec($ch);
    curl_close($ch);
    $directoryName = '../../chat/';
    if (is_dir($directoryName)) {
    } else {
        mkdir($directoryName, 0755, true);
    }
    $filename = $directoryName . $chatId . $telegramUserName . '.txt';
    // The string you want to write
    $stringToWrite = 'NutriAi: ' . $userdata->message . '\n';
    // Use file_put_contents with FILE_APPEND to append to the file
    file_put_contents($filename, $stringToWrite, FILE_APPEND | LOCK_EX);
    return $response;
}

function getChatFile($chatId, $telegramUserName) {

    $directoryName = '../../chat/';
    if (is_dir($directoryName)) {
    } else {
        mkdir($directoryName, 0755, true);
    }
    $filename = $directoryName . $chatId . $telegramUserName . '.txt';
    $content  = file_get_contents($filename);
    return $content;
}


function getClients($conn, $userId) {
    $names             = array();
    $ids               = array();
    $phoneNumbers      = array();
    $telegramUserNames = array();
    $telegramNewChats  = array();
    $genders           = array();
    $tablename         = 'userAllocation';
    $sql               = "SELECT clientId, name, phoneNumber, gender, telegramUserName, telegramNewChat FROM $tablename WHERE userId = '$userId';";
    $db_out            = $conn->query($sql);
    while($row = $db_out->fetch_assoc()) {
        array_push($names, $row['name']);
        array_push($telegramUserNames, $row['telegramUserName']);
        array_push($ids, $row['clientId']);
        array_push($phoneNumbers, $row['phoneNumber']);
        array_push($genders, $row['gender']);
        array_push($telegramNewChats, $row['telegramNewChat']);
    }
    $userInfo['names']             = $names;
    $userInfo['ids']               = $ids;
    $userInfo['genders']           = $genders;
    $userInfo['phoneNumbers']      = $phoneNumbers;
    $userInfo['telegramUserNames'] = $telegramUserNames;
    $userInfo['telegramNewChats']  = $telegramNewChats;
    return $userInfo;
}

function getTelegramChatIdfromDb($conn, $userId, $telegramUserName) {
    $tablename    = 'userAllocation';
    $sql          = "SELECT telegramChatId FROM $tablename WHERE userId = '$userId' AND telegramUserName = '$telegramUserName';";
    $db_out       = $conn->query($sql);
    $db_out       = $db_out->fetch_assoc();
    return $db_out['telegramChatId'];
}

function setClientPhoneNumber($conn, $userId, $cId, $phoneNumber) {
    $tablename    = 'userAllocation';
    $sql          = "UPDATE $tablename SET phoneNumber = '$phoneNumber' WHERE userId = '$userId' AND clientId = '$cId';";
    $db_out       = $conn->query($sql);
    return true;
}
function setClientTelegramUserName($conn, $userId, $cId, $username) {
    $tablename    = 'userAllocation';
    $sql          = "UPDATE $tablename SET telegramUserName = '$username' WHERE userId = '$userId' AND clientId = '$cId';";
    $db_out       = $conn->query($sql);
    return true;
}

function flushNewTelegramChat($conn, $telegramUserName) {
    $tablename   = 'userAllocation';
    $sql         = "UPDATE $tablename SET telegramNewChat = 0 WHERE telegramUserName = '$telegramUserName';";
    $db_out      = $conn->query($sql);
    return true;
}

// Replace these with your actual credentials
$userInfo      = json_decode($_POST['userInfo']);
// server connect
$servername  = "127.0.0.1";
$loginname   = "root";
$password    = "@Ssia123";
$dbname      = "Users";
$conn        = new mysqli($servername, $loginname, $password, $dbname);
// --------
if($userInfo->flag == 'getClients') {
    $response = getClients($conn, $userInfo->userId);
} elseif($userInfo->flag == 'addClientsNumber'){
    $response = setClientPhoneNumber($conn, $userInfo->userId, $userInfo->clientId, $userInfo->phoneNumber);
} elseif($userInfo->flag == 'addClientsTelegramUserName'){
    $response = setClientTelegramUserName($conn, $userInfo->userId, $userInfo->clientId, $userInfo->telegramUserName);
} elseif($userInfo->flag == 'send') {
    if($userInfo->chatArea == '0'){
        $response = sendTelegramMessage($conn, $userInfo);
    } elseif($userInfo->chatArea == '1') {
        $response = sendZalo($userInfo);
    }
} elseif($userInfo->flag == 'getChatFile') {
    $chatId   = getTelegramChatIdfromDb($conn, $userInfo->userId, $userInfo->telegramUserName);
    $response = getChatFile($chatId, $userInfo->telegramUserName);
    flushNewTelegramChat($conn, $userInfo->telegramUserName);
}
$conn->close();
echo json_encode($response);

?>

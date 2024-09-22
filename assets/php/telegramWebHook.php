<?php


handleWebHook();


function handleWebHook() {
    // Get the incoming update from Telegram
    $content = file_get_contents("php://input");
    $update = json_decode($content, true);
    // Check if the update has a message
    if (isset($update['message'])) {
        $chatId   = $update['message']['chat']['id'];
        $username = $update['message']['from']['username'];
        $text     = $update["message"]["text"];
        $textTime = $update["message"]["date"];
        setTelegramChatIdToDb($chatId, $username);
        $directoryName = '../../chat/';
        if (is_dir($directoryName)) {
        } else {
            mkdir($directoryName, 0755, true);
        }
        $filename = $directoryName . $chatId . $username . '.txt';
        // The string you want to write
        $stringToWrite = 'user: ' . $text . '\n';
        // Use file_put_contents with FILE_APPEND to append to the file
        file_put_contents($filename, $stringToWrite, FILE_APPEND | LOCK_EX);
    }
}

function setTelegramChatIdToDb($chatId, $telegramUserName) {
    // server connect
    $servername  = "127.0.0.1";
    $loginname   = "root";
    $password    = "@Ssia123";
    $dbname      = "Users";
    $conn        = new mysqli($servername, $loginname, $password, $dbname);
    $tablename   = 'userAllocation';
    $sql         = "UPDATE $tablename SET telegramChatId = '$chatId' WHERE telegramUserName = '$telegramUserName';";
    $db_out      = $conn->query($sql);
    $sql         = "UPDATE $tablename SET telegramNewChat = 1 WHERE telegramUserName = '$telegramUserName';";
    $db_out      = $conn->query($sql);
    return true;
}


?>

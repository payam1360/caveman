<?php

function saveUserComment($clientId, $userId, $comment, $category) {
    
    $servername   = "127.0.0.1";
    $loginname    = "root";
    $password     = "@Ssia123";
    $dbname       = "Users";
    $tablename    = "userAllocation";
    // Create connection
    $conn         = new mysqli($servername, $loginname, $password, $dbname);
    // check if the client exists.
    if($category == 'Bmi') {
        $field = 'descBmi';
    } elseif($category == 'Macro') {
        $field = 'descMacro';
    } elseif($category == 'Micro') {
        $field = 'descMicroTrace';
    } elseif($category == 'MicroVit') {
        $field = 'descMicroVit';
    } elseif($category == 'Meal') {
        $field = 'descMeal';
    } elseif($category == 'If') {
        $field = 'descIf';
    } elseif($category == 'campaign') {
        $field = 'campaignId';
    }
    $sql = "UPDATE $tablename SET 
                                            $field = '$comment'  
                                            WHERE 
                                            userId = '$userId' 
                                            AND 
                                            clientId = '$clientId';"; 
    $database_out = $conn->query($sql);
    return 0;
    
}

function createCampaignPageForClient($userId, $clientId, $campaignId, $category){
    if($clientId != '' && $category == 'campaign') {
        $s = $userId . $campaignId;
        $t = $userId . $clientId . $campaignId;
        copy("../../userPages/$s.html", "../../userPages/$t.html");
        $file = fopen("../../userPages/$t.html", "c");
        fseek($file, -21, SEEK_END);
        fwrite($file, $clientId);
        fwrite($file, "</p>\n");
        $text = "</body>\n";
        fwrite($file, $text);
        $text = "</html>\n";
        fwrite($file, $text);
        fclose($file);

        // updating the User table for the client page as well:
        $servername   = "127.0.0.1";
        $loginname    = "root";
        $password     = "@Ssia123";
        $dbname       = "Users";
        $tablename    = "Users";
        // Create connection
        $conn         = new mysqli($servername, $loginname, $password, $dbname);
        $sql          = "SELECT * FROM $tablename WHERE userId = '$userId' AND clientId = ''         AND campaignId = '$campaignId' AND qAnswer = '';";
        $existSql     = "SELECT * FROM $tablename WHERE userId = '$userId' AND clientId = '$clientId' AND campaignId = '$campaignId' AND qAnswer = '';";
        $ex           = $conn->query($existSql)->fetch_assoc();
        $database_out = $conn->query($sql);
        while($database_row = $database_out->fetch_assoc()) {
            $qKey        = $database_row['qKey'];
            $qIdx        = $database_row['qIdx'];
            $qType       = $database_row['qType'];
            $qContent    = $database_row['qContent'];
            $qAnswer     = $database_row['qAnswer'];
            $options     = $database_row['options'];
            $optionsText = $database_row['optionsText'];
            $visited     = $database_row['visited'];
            $qRequired   = $database_row['qRequired'];
            if($ex == null) {
                $insertSql    = "INSERT INTO $tablename (userId, clientId, ip, campaignId, qIdx, qType, qContent, qAnswer, options, optionsText, visited, qRequired, qKey) VALUES('$userId','$clientId', '', '$campaignId', '$qIdx', '$qType', '$qContent', '$qAnswer', '$options', '$optionsText', '$visited', '$qRequired', '$qKey');";
                $conn->query($insertSql);
            }
        }

    }
}


/// -------------------------
/// main routin starts here.
/// -------------------------
$userdata = json_decode($_POST['userInfo']);
$category = isset($userdata->topic) ? $userdata->topic : ''; 
$comment  = isset($userdata->clientText) ? $userdata->clientText : ''; 
$userId   = isset($userdata->userId) ? $userdata->userId : '';
$clientId = isset($userdata->clientId) ? $userdata->clientId : '';
createCampaignPageForClient($userId, $clientId, $comment, $category);
$status   = saveUserComment($clientId, $userId, $comment, $category);

echo json_encode($status);


?>

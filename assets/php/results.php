<?php


function extractUserInfo($userId) {
    
    $servername   = "127.0.0.1";
    $loginname    = "root";
    $password     = "@Ssia123";
    $dbname       = "Users";
    $table1name   = "userAllocation";
    $table2name   = "Users";
    // Create connection
    $conn         = new mysqli($servername, $loginname, $password, $dbname);
    // check if the client exists.
    $sql          = "SELECT * FROM $table1name WHERE userId = '$userId';";
    $database_out = $conn->query($sql);
    $ids         = array();
    $names       = array();
    $genders     = array();
    $goals       = array();
    $campaignids = array();
    $formFlag    = array();
    $kk        = 0;
    while($database_row = $database_out->fetch_assoc()) {
        // filling out info briefs
        array_push($names, $database_row['name']);
        array_push($ids, $database_row['clientId']);
        array_push($formFlag, $database_row['completed']);
        if($database_row['gender'] == 0) {
            array_push($genders, 'Male');
        } elseif($database_row['gender'] == 1){
            array_push($genders, 'Female');
        } else {
            array_push($genders, 'No response');
        }
        if($database_row['goal'] == 0) {
            array_push($goals, 'Increase testosterone');
        } elseif($database_row['goal'] == 1) {
            array_push($goals, 'Increase muscle mass');
        } elseif($database_row['goal'] == 2) {
            array_push($goals, 'Lose weight');
        } else {
            array_push($goals, 'No response');
        }
        array_push($campaignids, $database_row['campaignId']);
    }
    
    $userInfo['names']       = $names;
    $userInfo['ids']         = $ids;
    $userInfo['genders']     = $genders;
    $userInfo['goals']       = $goals;
    $userInfo['campaignids'] = $campaignids;
    $userInfo['formWasCreated'] = $formFlag;

    return $userInfo;
}


/// -------------------------
/// main routin starts here.
/// -------------------------
$userdata      = json_decode($_POST['userInfo']);
$userInfo      = extractUserInfo($userdata->userId);
echo json_encode($userInfo);


?>



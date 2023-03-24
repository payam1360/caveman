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
    $kk        = 0;
    while($database_row = $database_out->fetch_assoc()) {
        // filling out info briefs
        array_push($names, $database_row['name']);
        array_push($ids, $database_row['clientId']);
        if($database_row['gender'] == 0) {
            array_push($genders, 'Male');
        } elseif($database_row['gender'] == 1){
            array_push($genders, 'Female');
        } else {
            array_push($genders, '');
        }
        if($database_row['goal'] == 0) {
            array_push($goals, 'increase testosterone');
        } elseif($database_row['goal'] == 1) {
            array_push($goals, 'increase muscle mass');
        } elseif($database_row['goal'] == 2) {
            array_push($goals, 'lose weight');
        }
        array_push($campaignids, $database_row['campaignId']);

        /*
        if(empty($clients)) {
            array_push($clients, $database_row['clientId']);
        } elseif($kk > 0 && $clients[$kk] != $clients[$kk - 1]){
            array_push($clients, $database_row['clientId']);
        }
        // filling out its attributes
        if(str_contains($database_row['qKey'], 'weight')) {
            array_push($weights, $database_row['qAnswer']);
        }
        if(str_contains($database_row['qKey'], 'height')) {
            array_push($heights, $database_row['qAnswer']);
        }
        if(str_contains($database_row['qKey'], 'goal')) {
            array_push($goals, $database_row['qAnswer']);
        }
         */
    }
    
    $userInfo['names']       = $names;
    $userInfo['ids']         = $ids;
    $userInfo['genders']     = $genders;
    $userInfo['goals']       = $goals;
    $userInfo['campaignids'] = $campaignids;

    return $userInfo;
}


/// -------------------------
/// main routin starts here.
/// -------------------------
$userdata      = json_decode($_POST['userInfo']);
$userInfo      = extractUserInfo($userdata->userId);
echo json_encode($userInfo);


?>



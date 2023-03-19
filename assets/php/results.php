<?php


function extractUserInfo($userId) {
    
    $servername   = "127.0.0.1";
    $loginname    = "root";
    $password     = "@Ssia123";
    $dbname       = "Users";
    $table1name   = "Users";
    // Create connection
    $conn         = new mysqli($servername, $loginname, $password, $dbname);
    // check if the client exists.
    $sql          = "SELECT * FROM $table1name WHERE userId = '$userId';";
    $database_out = $conn->query($sql);
    $clients      = array();
    $weights      = array();
    $heights      = array();
    $goals        = array();
    $kk           = 0;
    while($database_row = $database_out->fetch_assoc()) {
        // filling out new user
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
    }
    
    $userInfo['clients'] = $clients;
    $userInfo['weights'] = $weights;
    $userInfo['heights'] = $heights;
    $userInfo['goal']    = $goals;
    
    return $userInfo;
}


/// -------------------------
/// main routin starts here.
/// -------------------------
$userdata      = json_decode($_POST['userInfo']);
$userInfo      = extractUserInfo($userdata->userId);
echo json_encode($userInfo);


?>



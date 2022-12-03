<?php
define("DBG", false);
define("MAX_cnt", 6);
// class user
class user {
    var $name;
    var $email;
    var $id;
    var $questions;
    function set_name($value) {
        $this->name = $value;
    }
    function get_name() {
        return $this->name;
    }
};


function saveUserDataIntoDB($Questions) {
       
    $servername  = "127.0.0.1";
    $loginname   = "root";
    $password    = "@Ssia123";
    $dbname      = "Users";
    $table1name  = "Users";
    // Create connection
    $conn        = new mysqli($servername, $loginname, $password, $dbname);
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    if(DBG) {
        echo "Connected successfully";
    }

    for($kk = 0; $kk < MAX_cnt; $kk++){
        $options = "";
        if($Questions[$kk]->options == ""){
        } else {
            for($kx = 0; $kx < count($Questions[$kk]->options); $kx++){
                $options = $options . "," . $Questions[$kk]->options[$kx];
            }
        }
        $sql = $sql = "INSERT INTO " . $table1name . " (userid, Qidx, type, question, answer, options) VALUES('" . $Questions[$kk]->userid . "','" .  $Questions[$kk]->Qidx . "','" . $Questions[$kk]->type . "','" . $Questions[$kk]->question . "','" . $Questions[$kk]->answer . "','" . $options . "')";
        if(DBG) {
            echo $sql;
        }
        
        if ($conn->query($sql) === TRUE and DBG) {
            echo "New record created successfully";
        } elseif(DBG) {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }
    $conn->close();
    return true;
}

/// -------------------------
/// main routin starts here.
/// -------------------------
$userdata      = json_decode($_POST['userInfo']);
$dbflag        = saveUserDataIntoDB($userdata);
if($dbflag == false and DBG) {
    echo "user has not registered. no data is saved";
}elseif(DBG){
    echo "user data is saved.\n";
}

//$user_info   = new user;
//$user_info   = estimatealluserparams($userwaist, $userthigh, $userinseam, $useroutseam, $userstyle);
//$Alldata     = fetchdata();
//$bestfit_idx = solveLS($Alldata, $user_info, $userpricemin, $userpricemax, NUM_BEST_FIT);
//$figureMerit = calculateFigureofMerit($Alldata, $user_info, $userpricemin, $userpricemax);
//$DataBlob    = CreateBlob4Js($Alldata, $figureMerit, $bestfit_idx, $userpricemax, $userpricemin, $username);


//echo json_encode($DataBlob);


?>


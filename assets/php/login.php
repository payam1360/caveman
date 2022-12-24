<?php

define("DBG", false);
define("MAX_cnt", 2);
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


function dataPrep($ok){
    if($ok === 1) {
        $data = array('ok' => true);
        } else {
            $data = array('ok' => false);
        }
    return $data;
}

/// -------------------------
/// main routin starts here.
/// -------------------------
$userdata      = json_decode($_POST['userInfo']);
$dbflag        = validateUserCredentials($userdata);
if($dbflag == false and DBG) {
    echo "user has not registered. no data is saved";
}elseif(DBG){
    echo "user data is saved.\n";
}

$data          = dataPrep();
echo json_encode($data);


?>


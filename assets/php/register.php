<?php

define("PLAN", 0);
define("NAME", 1);
define("EMAIL", 2);
define("VERC", 4);
define("PASS", 6);
define("PASSC", 7);
define("STRP", 8);

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../../vendor/autoload.php';
require '../../vendor/phpmailer/phpmailer/src/SMTP.php';
require '../../vendor/phpmailer/phpmailer/src/PHPMailer.php';
require '../../vendor/phpmailer/phpmailer/src/Exception.php';

function createClientIdandCampaign($email, $numAllocation, $numCampaign, $accountType) {
    
    $servername  = "127.0.0.1";
    $loginname   = "root";
    $password    = "@Ssia123";
    $dbname      = "Users";
    $table1name  = "userAllocation";
    $table2name  = "authentication";
    $conn        = new mysqli($servername, $loginname, $password, $dbname);
    $sql         = "SELECT userId FROM $table2name WHERE email = '$email';";
    $data        = $conn->query($sql);
    $userId      = $data->fetch_column(0);
    $sql         = "UPDATE " . $table2name . " SET accountType = '" . $accountType . "' WHERE email = '" . $email . "';";
    $conn->query($sql);
    for($kk = 0; $kk < $numAllocation; $kk++) {
        $clientId    = mt_rand(10000, 99999);
        if($kk < $numCampaign) {
            $campaignId  = substr(md5(rand()), 0, 7);
        } else {
            $campaignId = '';
        }
        $sql         = "INSERT INTO $table1name (userId, clientId, campaignId, campaignIdSource, used, completed, name, gender, goal, nutritionEng, mealEng, descBmi, descBmr, descIf, descMacro, descMicroTrace, descMicroVit) VALUES('$userId','$clientId','', '$campaignId', '0', '0', '', '', '', '', '', '', '', '', '', '', '');";
        $conn->query($sql);
    }
    $conn->close();
    
}

function registerUserCredentials($passwordUser, $email) {
       
    $servername  = "127.0.0.1";
    $loginname   = "root";
    $password    = "@Ssia123";
    $dbname      = "Users";
    $tablename   = "authentication";
    // Create connection
    $conn        = new mysqli($servername, $loginname, $password, $dbname);
    $hash        = password_hash($passwordUser, PASSWORD_DEFAULT);
    $sql         = "UPDATE " . $tablename . " SET password = '" . $hash . "' WHERE email = '" . $email . "';";
    $conn->query($sql);
    $conn->close();
}


function sendEmail($emailAddr) {
    $mail = new PHPMailer(true);

    // app password: azqb ochq lfot btnc


    // Server settings
    $mail->SMTPDebug = 0;                       //Enable verbose debug output
    $mail->isSMTP();                            // Set mailer to use SMTP
    $mail->Host = 'smtp.gmail.com';             // Specify main and backup SMTP servers
    $mail->SMTPAuth = true;                     // Enable SMTP authentication
    $mail->Username = 'rabiei.p@gmail.com'; // SMTP username
    $mail->Password = 'azqb ochq lfot btnc';    // SMTP password
    $mail->SMTPSecure = 'ssl';                  // Enable TLS encryption, `ssl` also accepted
    $mail->Port = 465;                          // TCP port to connect to
    // Sender info
    $mail->setFrom('rabiei.p@gmail.com', 'Payam Rabiei');
    // Add a recipient
    $mail->addAddress($emailAddr);
    // Set email format to HTML
    $mail->isHTML(true);
    // Mail subject
    $mail->Subject = 'Verify your email';
    $verification_code = mt_rand(10000, 99999);
    // Mail body content
    $bodyContent  = 'your verification code is: <b>' . $verification_code . '</b>';
    $bodyContent .= '<p>This email is sent from Nutrition4guys </p>';
    $mail->Body   = $bodyContent;
    // Send email
    if(!$mail->send()) {
        echo 'Message could not be sent. Mailer Error: '.$mail->ErrorInfo;
    } else {
    }
    return $verification_code;
}

function saveVerificationAndEmail($verification_code, $email, $name) {
    $servername  = "127.0.0.1";
    $loginname   = "root";
    $password    = "@Ssia123";
    $dbname      = "Users";
    $tablename   = "authentication";
    $conn        = new mysqli($servername, $loginname, $password, $dbname);
    $userId      = mt_rand(100000, 999999);
    $sql         = "INSERT INTO " . $tablename . " (userId, name, email, password, verification, accountType, emailVer, passVer, payVer) VALUES('" . $userId . "','" . $name . "','" .  $email . "','" . "','" . $verification_code . "', '', '0', '0', '0');";
    $conn->query($sql);
    $conn->close();
}
function readVerification($email) {
    $servername  = "127.0.0.1";
    $loginname   = "root";
    $password    = "@Ssia123";
    $dbname      = "Users";
    $tablename   = "authentication";
    $conn        = new mysqli($servername, $loginname, $password, $dbname);
    $sql         = "SELECT verification FROM $tablename WHERE email = '$email';";
    $verification_code = $conn->query($sql);
    $conn->close();
    return $verification_code->fetch_column(0);
}

function checkEmailExists($email) {
    $servername  = "127.0.0.1";
    $loginname   = "root";
    $password    = "@Ssia123";
    $dbname      = "Users";
    $tablename   = "authentication";
    $conn        = new mysqli($servername, $loginname, $password, $dbname);
    $sql         = "SELECT EXISTS (SELECT userId FROM $tablename WHERE email = '$email');";
    $ex          = $conn->query($sql);
    if($ex->fetch_column(0) == 0){
        $ex = false;
    } else {
        $ex = true;
    }
    $conn->close();
    return $ex;
    
}

function getVerification($email) {
    $servername  = "127.0.0.1";
    $loginname   = "root";
    $password    = "@Ssia123";
    $dbname      = "Users";
    $tablename   = "authentication";
    $conn        = new mysqli($servername, $loginname, $password, $dbname);
    $sql         = "SELECT emailVer, passVer, payVer FROM $tablename WHERE email = '$email';";
    $data        = $conn->query($sql);
    $row         = $data->fetch_assoc(); 
    if($email == '' || !isset($row)) {
        $verified_dB['email'] = 0;
        $verified_dB['pass']  = 0;
        $verified_dB['pay']   = 0;
    } else {
        $verified_dB['email'] = $row['emailVer'];
        $verified_dB['pass']  = $row['passVer'];
        $verified_dB['pay']   = $row['payVer'];
    }
    return($verified_dB);
}
function updateEmailVerification($email) {
    $servername  = "127.0.0.1";
    $loginname   = "root";
    $password    = "@Ssia123";
    $dbname      = "Users";
    $tablename   = "authentication";
    $conn        = new mysqli($servername, $loginname, $password, $dbname);
    $sql         = "UPDATE $tablename SET emailVer = 1 WHERE email = '$email';";
    $conn->query($sql);
}
function updatePassVerification($email) {
    $servername  = "127.0.0.1";
    $loginname   = "root";
    $password    = "@Ssia123";
    $dbname      = "Users";
    $tablename   = "authentication";
    $conn        = new mysqli($servername, $loginname, $password, $dbname);
    $sql         = "UPDATE $tablename SET passVer = 1 WHERE email = '$email';";
    $conn->query($sql);
}
/// -------------------------
/// main routin starts here.
/// -------------------------
$userdata  = json_decode($_POST['userInfo']);
if($userdata[PLAN]->qAnswer == '0') {
    $data['MAX_cnt'] = 9;
} elseif($userdata[PLAN]->qAnswer == '1') {
    $data['MAX_cnt'] = 10;
} elseif($userdata[PLAN]->qAnswer == '2') {
    $data['MAX_cnt'] = 10;
}
$verified_dB = getVerification($userdata[EMAIL]->qAnswer);
if($verified_dB['email'] == 0) {
    if($userdata[EMAIL]->qAnswer != '' && $userdata[VERC]->qAnswer == '') { // if email is provided send verification code
        $ex = checkEmailExists($userdata[EMAIL]->qAnswer);
        if($ex == false) {
            $verification_code = sendEmail($userdata[EMAIL]->qAnswer);
            saveVerificationAndEmail($verification_code, $userdata[EMAIL]->qAnswer, $userdata[NAME]->qAnswer);
            $data['status'] = 5; // new user
        } else {
            $data['status'] = 2; // user already exists but verification not done yet
        }
    } elseif($userdata[VERC]->qAnswer != '') { // if verification code is provided check the code
        $verification_code = readVerification($userdata[EMAIL]->qAnswer);
        if($verification_code == $userdata[VERC]->qAnswer) {
            updateEmailVerification($userdata[EMAIL]->qAnswer);
            $data['status']    = 1; // email is verified
        } else {
            $data['status']    = 3; // wrong verification code .. email not verified
        }
    } else {
        $data['status'] = 0;
    }
} elseif($verified_dB['email'] == 1 && $verified_dB['pass'] == 0) {
    if($userdata[PASS]->qAnswer == $userdata[PASSC]->qAnswer && $userdata[PASS]->qAnswer != '') {
        registerUserCredentials($userdata[PASS]->qAnswer, $userdata[EMAIL]->qAnswer);
        updatePassVerification($userdata[EMAIL]->qAnswer);
        if($userdata[PLAN]->qAnswer == "0") {
            $numClients = 10;
            $numCampaign = 1;
            $accountType = 'free';
            $data['status'] = 6;
        } elseif($userdata[PLAN]->qAnswer == "1") {
            $numClients = 20;
            $numCampaign = 2;
            $accountType = 'delux';
            $data['status'] = 7;
        } elseif($userdata[PLAN]->qAnswer == "2") {
            $numClients = 50;
            $numCampaign = 5;
            $accountType = 'premium';
            $data['status'] = 7;
        } 
        createClientIdandCampaign($userdata[EMAIL]->qAnswer, $numClients, $numCampaign, $accountType);
    } elseif($userdata[PASS]->qAnswer != $userdata[PASSC]->qAnswer && $userdata[PASS]->qAnswer != '' && $userdata[PASSC]->qAnswer != '') {
        $data['status'] = 4; // password not matching 
    } else {
        $data['status'] = 0; 
    }
} elseif($verified_dB['email'] == 1 && $verified_dB['pass'] == 1 && ($verified_dB['payment'] == 1 || $data['MAX_cnt'] == 9))  { // payment not required or payment successful
    $data['status'] = 8; 
} elseif($verified_dB['email'] == 1 && $verified_dB['pass'] == 1 && $verified_dB['payment'] == 0) { // payment not successful
    $data['status'] = 9; 
} else {
    $data['status'] = 0; 
}

echo json_encode($data);
?>


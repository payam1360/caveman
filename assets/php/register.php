<?php

define("PLAN", 0);
define("NAME", 1);
define("EMAIL", 2);
define("VERC", 4);
define("PASS", 6);
define("PASSC", 7);

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../../vendor/autoload.php';
require '../../vendor/phpmailer/phpmailer/src/SMTP.php';
require '../../vendor/phpmailer/phpmailer/src/PHPMailer.php';
require '../../vendor/phpmailer/phpmailer/src/Exception.php';

function createClientIdandCampaign($email, $numAllocation) {
    
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
    for($kk = 0; $kk < $numAllocation; $kk++){
        $clientId    = mt_rand(10000, 99999);
        $campaignId  = substr(md5(rand()), 0, 7);
        $sql         = "INSERT INTO $table1name (userId, clientId, campaignId, used, completed, name, gender, goal, nutritionEng, mealEng, descBmi, descBmr, descIf, descMacro, descMicroTrace, descMicroVit) VALUES('$userId','$clientId','$campaignId', '0', '0', '', '', '', '', '', '', '', '', '', '', '');";
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
    $sql         = "INSERT INTO " . $tablename . " (userId, name, email, password, verification) VALUES('" . $userId . "','" . $name . "','" .  $email . "','" . "','" . $verification_code . "');";
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
    $sql         = "SELECT verification " . "FROM " . $tablename . " WHERE email = '" . $email . "';";
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
/// -------------------------
/// main routin starts here.
/// -------------------------
$userdata  = json_decode($_POST['userInfo']);
$data['status']    = -1;
$data['verification'] = '';
$verified = false;
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
        $verified = true;
        $data['status']    = 1; // email is verified
    } else {
        $data['status']    = 3; // wrong verification code .. email not verified
    }
}
if($verified == 1) {
    if($userdata[PASS]->qAnswer == $userdata[PASSC]->qAnswer && $userdata[PASS]->qAnswer != '') {
        registerUserCredentials($userdata[PASS]->qAnswer, $userdata[EMAIL]->qAnswer);
        if($userdata[PLAN]->qAnswer == "0") {
            $numClients = 10;
        } elseif($userdata[PLAN]->qAnswer == "1") {
            $numClients = 20;
        } elseif($userdata[PLAN]->qAnswer == "2") {
            $numClients = 50;
        }
        createClientIdandCampaign($userdata[EMAIL]->qAnswer, $numClients);
        $data['status'] = 0; // password good ... user is registered
    } elseif($userdata[PASS]->qAnswer != $userdata[PASSC]->qAnswer && $userdata[PASS]->qAnswer != '') {
        $data['status'] = 4; // password not matching 
    }
}
echo json_encode($data);
?>


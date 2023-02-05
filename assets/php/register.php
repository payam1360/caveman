<?php

define("DBG", false);
define("MAX_cnt", 3);

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../../vendor/autoload.php';
require '../../vendor/SMTP.php';
require '../../vendor/PHPMailer.php';
require '../../vendor/Exception.php';


function registerUserCredentials($password, $email) {
       
    $servername  = "127.0.0.1";
    $loginname   = "root";
    $password    = "@Ssia123";
    $dbname      = "Users";
    $tablename   = "authentication";
    // Create connection
    $conn        = new mysqli($servername, $loginname, $password, $dbname);
    $hash        = password_hash($password, PASSWORD_DEFAULT);
    $sql         = "UPDATE " . $tablename . " SET password = '" . $hash . "' WHERE email = '" . $email . "';";
    $conn->query($sql);
    $conn->close();
}


function sendEmail($emailAddr) {
    $mail = new PHPMailer(true);
    // Server settings
    $mail->SMTPDebug = 3;                       //Enable verbose debug output
    $mail->isSMTP();                            // Set mailer to use SMTP
    $mail->Host = 'smtp.hostinger.com';                  // Specify main and backup SMTP servers
    $mail->SMTPAuth = true;                     // Enable SMTP authentication
    $mail->Username = 'payam@nutrition4guys.com';     // SMTP username
    $mail->Password = '@Brcm123';               // SMTP password
    $mail->SMTPSecure = 'ssl';                  // Enable TLS encryption, `ssl` also accepted
    $mail->Port = 465;                          // TCP port to connect to
    // Sender info
    $mail->setFrom('payam@nutrition4guys.com', 'Payam Rabiei');
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
    
    //if(!$mail->send()) {
    //    echo 'Message could not be sent. Mailer Error: '.$mail->ErrorInfo;
    //} else {
    //    echo 'Message has been sent.';
    //}
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
    $sql         = "SELECT EXISTS (SELECT userId FROM " . $tablename . " WHERE email = '" . $email . "');";
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
$data['status']    = 1;
$data['verification'] = '';
$verified = false;

if($userdata[1]->qAnswer != '' && $userdata[2]->qAnswer == '') { // if email is provided send verification code
    $ex = checkEmailExists($userdata[1]->qAnswer);
    if($ex == false) {
        $verification_code = sendEmail($userdata[1]->qAnswer);
        saveVerificationAndEmail($verification_code, $userdata[1]->qAnswer, $userdata[0]->qAnswer);
        $data['verification'] = $verification_code;
    } else {
        $data['status'] = 2;
    }
} elseif($userdata[2]->qAnswer != '') { // if verification code is provided check the code
    $verification_code = readVerification($userdata[1]->qAnswer);
    if($verification_code == $userdata[2]->qAnswer) {
        $verified = true;
    }
}
if($verified == true) {
    if($userdata[3]->qAnswer == $userdata[4]->qAnswer && $userdata[3]->qAnswer != '') {
        registerUserCredentials($userdata[3]->qAnswer, $userdata[1]->qAnswer);
        $data['status'] = 0;
    } elseif($userdata[3]->qAnswer != $userdata[4]->qAnswer && $userdata[3]->qAnswer != '') {
        $data['status'] = 1;
    }
}
echo json_encode($data);
?>


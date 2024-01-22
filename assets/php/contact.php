<?php


use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../../vendor/autoload.php';
require '../../vendor/phpmailer/phpmailer/src/SMTP.php';
require '../../vendor/phpmailer/phpmailer/src/PHPMailer.php';
require '../../vendor/phpmailer/phpmailer/src/Exception.php';

function sendEmail($emailAddr, $bodyContent) {
    $mail = new PHPMailer(true);

    // app password: azqb ochq lfot btnc
    $emailAddr = 'rabiei.p@gmail.com';
    // Server settings
    $mail->SMTPDebug = 0;                         //Enable verbose debug output
    $mail->isSMTP();                              // Set mailer to use SMTP
    $mail->Host       = 'smtp.gmail.com';         // Specify main and backup SMTP servers
    $mail->SMTPAuth   = true;                     // Enable SMTP authentication
    $mail->Username   = 'rabiei.p@gmail.com';     // SMTP username
    $mail->Password   = 'azqb ochq lfot btnc';    // SMTP password
    $mail->SMTPSecure = 'ssl';                    // Enable TLS encryption, `ssl` also accepted
    $mail->Port       = 465;                      // TCP port to connect to
    // Sender info
    $mail->setFrom('rabiei.p@gmail.com', 'Payam Rabiei');
    // Add a recipient
    $mail->addAddress($emailAddr);
    // Set email format to HTML
    $mail->isHTML(true);
    // Mail subject
    $mail->Subject = 'Nutritional progress report';
    // Mail body content
    $mail->Body    = $bodyContent;
    // Send email
    if(!$mail->send()) {
        echo 'Message could not be sent. Mailer Error: '.$mail->ErrorInfo;
        $data['status']    = -1;
    } else {
        $data['status']    = 0;
    }
    return $data;
}

function getClientEmail($userId, $clientId) {
    // return client email address
    $servername  = "127.0.0.1";
    $loginname   = "root";
    $password    = "@Ssia123";
    $dbname      = "Users";
    $tablename   = "Users";
    // Create connection
    $conn        = new mysqli($servername, $loginname, $password, $dbname);
    // check if the client exists.
    $sql         = "SELECT qAnswer FROM $tablename WHERE userId = '$userId' AND clientId = '$clientId' AND qType = 'email';";
    $db_out      = $conn->query($sql);
    $clientEmail = $db_out->fetch_assoc();
    return $clientEmail['qAnswer'];
}


function prepareBody($userdata) {
    $bodyContent = 'test';
    return $bodyContent;
}

/// -------------------------
/// main routin starts here.
/// -------------------------
$userData    = json_decode($_POST['userInfo']);
$clientEmail = getClientEmail($userData->userId, $userData->clientId);
$bodyContent = prepareBody($userData);
$data        = sendEmail($clientEmail, $bodyContent);
echo json_encode($data);
?>
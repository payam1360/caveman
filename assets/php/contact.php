<?php


use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../../vendor/autoload.php';
require '../../vendor/phpmailer/phpmailer/src/SMTP.php';
require '../../vendor/phpmailer/phpmailer/src/PHPMailer.php';
require '../../vendor/phpmailer/phpmailer/src/Exception.php';


function sendEmailForm($emailAddr, $link) {
    $mail = new PHPMailer(true);
    $emailAddr = 'rabiei.p@gmail.com';
    // app password: azqb ochq lfot btnc
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
    $mail->Subject = 'NutriAi Campaign Form';
    // Mail body content
    $form_page     = $link;
    $bodyContent   = '<!DOCTYPE html>
    <html>
    <head>
        <style>
            body { font-family: Arial, sans-serif; }
            .container { width: 80%; margin: auto; }
            .header { background-color: #f4f4f4; padding: 10px; text-align: center; }
            .content { margin: 20px 0; }
            .link-button {
                display: flex;
                margin: auto;
                justify-content: center;
                text-align: center;
                padding-top: 20px;
            }
            .link-page {
                display: flex;
                background-color: dodgerblue;
                color: white;
                text-align: center;
                font-size: 16px;
                border: none;
                border-radius: 4px;
                cursor: pointer;
                padding: 12px 24px;
            }   
            .footer { background-color: #f4f4f4; padding: 10px; text-align: center; }
        </style>
              
    </head>
    <body>
        <div class="container">
            <div class="header">
                <h1>NutriAi Link to Campaign Form</h1>
            </div>
            <div class="content">
                <p style="font-size: 20px">Hello,</p>
                <p style="font-size: 20px">Here is the link to your form. please Answer few questions.</p>' .
                '<div class="link-button">
                    <a href="' . $form_page . '" class="link-page" target="_blank">Open</a>
                </div>'
                . '<p style="font-size: 20px">Best regards,<br>NutriAi team</p>
            </div>
            <div class="footer">
                <p>NutriAi | Carlsbad, Ca 92008 | 6129785987</p>
            </div>
        </div>
    </body>
    </html>';
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


function sendEmail($emailAddr, $bodyContent) {
    $mail = new PHPMailer(true);
    $emailAddr = 'rabiei.p@gmail.com';
    // app password: azqb ochq lfot btnc
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
    $table1name  = "userAllocation";
    // Create connection
    $conn        = new mysqli($servername, $loginname, $password, $dbname);
    // check if the client exists.
    $sql         = "SELECT qAnswer FROM $tablename WHERE userId = '$userId' AND clientId = '$clientId' AND qType = 'email';";
    $db_out      = $conn->query($sql);
    $clientEmail = $db_out->fetch_assoc();
    if(is_null($clientEmail)){ // check the other table to see if there is email for this client
        $sql         = "SELECT cEmail FROM $table1name WHERE userId = '$userId' AND clientId = '$clientId';";
        $db_out      = $conn->query($sql);
        $clientEmail = $db_out->fetch_assoc(); 
        $clientEmail = $clientEmail['cEmail']; 
    } else {
        $clientEmail = $clientEmail['qAnswer'];
    }
    return $clientEmail;
}

function resizeImage($sourceImagePath, $destinationImagePath, $newWidth) {
    // Get original image dimensions and type
    list($originalWidth, $originalHeight, $imageType) = getimagesize($sourceImagePath);
    if ($originalWidth === false || $originalHeight === false) {
        die("Unable to get image dimensions.");
    }

    // Determine aspect ratio and new dimensions
    $aspectRatio = $originalWidth / $originalHeight;
    $newHeight = round($newWidth / $aspectRatio);

    // Create an image resource from the source file
    switch ($imageType) {
        case IMAGETYPE_JPEG:
            $sourceImage = imagecreatefromjpeg($sourceImagePath);
            break;
        case IMAGETYPE_PNG:
            $sourceImage = imagecreatefrompng($sourceImagePath);
            break;
        case IMAGETYPE_GIF:
            $sourceImage = imagecreatefromgif($sourceImagePath);
            break;
        default:
            die("Unsupported image type.");
    }

    if (!$sourceImage) {
        die("Unable to create image resource from source.");
    }

    // Create a new true color image with the new dimensions
    $resizedImage = imagecreatetruecolor($newWidth, $newHeight);

    // Handle transparency for PNG and GIF images
    if ($imageType == IMAGETYPE_PNG || $imageType == IMAGETYPE_GIF) {
        // Preserve transparency
        imagealphablending($resizedImage, false);
        imagesavealpha($resizedImage, true);
        $transparent = imagecolorallocatealpha($resizedImage, 0, 0, 0, 127);
        imagefill($resizedImage, 0, 0, $transparent);
    }

    // Resize the source image and copy it into the resized image
    imagecopyresampled(
        $resizedImage, $sourceImage,
        0, 0, 0, 0,
        $newWidth, $newHeight,
        $originalWidth, $originalHeight
    );

    // Save the resized image to the destination path
    switch ($imageType) {
        case IMAGETYPE_JPEG:
            imagejpeg($resizedImage, $destinationImagePath);
            break;
        case IMAGETYPE_PNG:
            imagepng($resizedImage, $destinationImagePath);
            break;
        case IMAGETYPE_GIF:
            imagegif($resizedImage, $destinationImagePath);
            break;
    }

    // Free up memory
    imagedestroy($sourceImage);
    imagedestroy($resizedImage);
}



function prepareBody($userdata) {

    $ImageIds = array(
        'Bmi', 'IntermittentFasting', 'Micro', 'Micro_vit', 'Macro', 'Calories' 
    );
    $ImageNames = array(
        'bmi', 'if', 'microTrace', 'microVit', 'macro', 'cal' 
    );
    $outerHTML = $userdata->parentNode;
    for($kk = 0; $kk < count($ImageNames); $kk++){

        $sourceImagePath = '../../clientEmails/' . $userdata->userId . $userdata->clientId . $ImageNames[$kk] . 'File.png';
        $destinationImagePath = '../../clientEmails/resized' . $userdata->userId . $userdata->clientId . $ImageIds[$kk] . 'File.png';
        $newWidth = 512; // Desired width in pixels
        resizeImage($sourceImagePath, $destinationImagePath, $newWidth);
        // process $userdata->parentNode outerHTML for canvas and replace with src img  
        // Regular expression to find <canvas> tags
        $pattern   = '/<canvas id=\"' . $ImageIds[$kk] . '\"[^>]*>(.*?)<\/canvas>/is';
        $imagePath = $destinationImagePath;
        $imageData = base64_encode(file_get_contents($imagePath));
        $src       = 'data:image/png;base64,' . $imageData;
        // Replacement string
        // Here we use a placeholder image URL. You can adjust it as needed.
        $replacement = '<img src="' . $src . '"alt="Canvas Image" />';

        // Replace <canvas> tags with <img> tags
        $outerHTML = preg_replace($pattern, $replacement, $outerHTML);
    }
    // also remove buttons
    $outerHTML = preg_replace('/<button\b[^>]*>(.*?)<\/button>/is', '', $outerHTML);

    $bodyContent = '<!DOCTYPE html>
    <html>
    <head>
        <style>
            body { font-family: Arial, sans-serif; }
            .container { width: 80%; margin: auto; }
            .header { background-color: #f4f4f4; padding: 10px; text-align: center; }
            .content { margin: 20px 0; }
            .footer { background-color: #f4f4f4; padding: 10px; text-align: center; }
        </style>
              
    </head>
    <body>
        <div class="container">
            <div class="header">
                <h1>NutriAi Progress report</h1>
            </div>
            <div class="content">
                <p style="font-size: 20px">Hello,</p>
                <p style="font-size: 20px">Here is the report for your progress this past month.</p>' . 
                $outerHTML 
                . '<p style="font-size: 20px">Best regards,<br>NutriAi team</p>
            </div>
            <div class="footer">
                <p>NutriAi | Carlsbad, Ca 92008 | 6129785987</p>
            </div>
        </div>
    </body>
    </html>';
    return $bodyContent;
}
function saveImagesOnServer($data){

    $ImageNames = array(
        'bmi', 'if', 'microTrace', 'microVit', 'macro', 'cal' 
    );
    $ImageData = array(
        $data->bmiImg, $data->ifImg, $data->microTraceImg, $data->microVitImg, $data->macroImg, $data->calImg 
    );    
    $success = true;
    for($kk = 0; $kk < count($ImageNames); $kk++){
        $Img         = $ImageData[$kk];
        $Img         = str_replace('data:image/png;base64,', '', $Img);
        $Img         = str_replace(' ', '+', $Img);
        $imgData     = base64_decode($Img);
        $imgFile     = '../../clientEmails/' . $data->userId . $data->clientId . $ImageNames[$kk] . 'File.png';
        $success     = $success && file_put_contents($imgFile, $imgData);
    }
    return $success;
}

function cleanup(){
    $directory = '../../clientEmails';

    if (is_dir($directory)) {
        $files = array_diff(scandir($directory), array('.', '..'));

        foreach ($files as $file) {
            $filePath = $directory . DIRECTORY_SEPARATOR . $file;

            if (is_file($filePath)) {
                unlink($filePath); // Deletes the file
            }
        }
    } else {
    }
}
/// -------------------------
/// main routin starts here.
/// -------------------------
$userData    = json_decode($_POST['userInfo']);
if($userData->flag == 'sendReport'){
    $success     = saveImagesOnServer($userData);
    if($success){
        $clientEmail = getClientEmail($userData->userId, $userData->clientId);
        $bodyContent = prepareBody($userData);
        $data        = sendEmail($clientEmail, $bodyContent);
    }
    // clean the clientEmail directory.
    cleanup();
} elseif($userData->flag == 'sendForm'){
    $clientEmail = getClientEmail($userData->userId, $userData->clientId);
    $link        = 'http://localhost/userPages/' . $userData->userId . $userData->clientId . $userData->campaignId . '.html';
    $data        = sendEmailForm($clientEmail, $link);
}
echo json_encode($data);
?>
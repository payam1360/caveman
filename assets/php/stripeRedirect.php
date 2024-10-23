<?php
require '../../vendor/autoload.php';

function getStripeSecretToken(){
    // server connect
    $servername  = "127.0.0.1";
    $loginname   = "root";
    $password    = "@Ssia123";
    $dbname      = "Users";
    $conn        = new mysqli($servername, $loginname, $password, $dbname);
    $tablename   = "admin";
    $sql         = "SELECT stripeSecretToken FROM $tablename;";
    $db_out      = $conn->query($sql);
    $stripeSecretToken = $db_out->fetch_assoc();
    $stripeSecretToken = $stripeSecretToken['stripeSecretToken'];
    return $stripeSecretToken;
}

$userInfo      = json_decode($_POST['userInfo']);

$stripeSecretToken = getStripeSecretToken();
\Stripe\Stripe::setApiKey($stripeSecretToken);
// Get the authorization code from the query string
$code = $_GET['code'];

// Exchange the authorization code for an access token
$tokenResponse = \Stripe\OAuth::token([
    'grant_type' => 'authorization_code',
    'code' => $code,
]);

// Retrieve the access token
$accessToken = $tokenResponse->access_token;
$stripeUserId = $tokenResponse->stripe_user_id;

//$stripe = new \Stripe\StripeClient($accessToken);
// save them in a file for immediately record them in the database
// Define the path where the file will be saved
$filePath = './stripeToken.txt';
// Use file_put_contents to write the text to the file
file_put_contents($filePath, $accessToken);
$filePath = './stripeId.txt';
file_put_contents($filePath, $stripeUserId);
// Redirect back to the original page
header("Location: http://localhost/finances.html?addUserStripeInfo=true");
exit();
?>
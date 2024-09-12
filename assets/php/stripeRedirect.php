<?php
require '../../vendor/autoload.php';

\Stripe\Stripe::setApiKey('sk_test_51Odb1JGvkwgMtml80Bc0CdBOesMqZzMeulH9j8QO03HnfrLniWn96gEYLK9QdLbmmXQ1voYVKBib06UaTdqxgzfP00P41SGnWu');
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
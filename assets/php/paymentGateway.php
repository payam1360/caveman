
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

function processPayment($data) {

    $stripeSecretKey = getStripeSecretToken();
    $stripe = new Stripe\StripeClient($stripeSecretKey);
    $customer = $stripe->customers->create([
        'description' => $data->name,
        'email' => $data->email,
        'payment_method' => 'pm_card_visa',
    ]);
    $paymentIntent = $stripe->paymentIntents->create([
        'amount' => 50,
        'currency' => 'usd',
        'automatic_payment_methods' => ['enabled' => true],
        'customer' => $customer->id,
    ]);
    $confirm = $stripe->paymentIntents->confirm(
        $paymentIntent->id,
        [
            'payment_method' => 'pm_card_visa',
            'return_url' => 'https://www.example.com',
        ]
    );
    return $confirm;
}

function updatePayVerification($email, $confirm_status) {
    $servername  = "127.0.0.1";
    $loginname   = "root";
    $password    = "@Ssia123";
    $dbname      = "Users";
    $tablename   = "authentication";
    $conn        = new mysqli($servername, $loginname, $password, $dbname);
    if($confirm_status == 'succeeded') {
        $sql     = "UPDATE $tablename SET payVer = 1 WHERE email = '$email';";
    } else {
        $sql     = "UPDATE $tablename SET payVer = 0 WHERE email = '$email';";
    }
    $conn->query($sql);
}

$userdata  = json_decode($_POST['userInfo']);
$data      = processPayment($userdata);
updatePayVerification($userdata->email, $data->status);

?>
<?php


require '../../vendor/autoload.php';


function processPayment($data) {

    $stripe = new Stripe\StripeClient('sk_test_51Odb1JGvkwgMtml80Bc0CdBOesMqZzMeulH9j8QO03HnfrLniWn96gEYLK9QdLbmmXQ1voYVKBib06UaTdqxgzfP00P41SGnWu');
    $stripe->customers->create([
        'description' => 'example customer',
        'email' => 'email@example.com',
        'payment_method' => 'pm_card_visa',
    ]);
    $paymentIntent = $stripe->paymentIntents->create([
        'amount' => 50,
        'currency' => 'usd',
        'automatic_payment_methods' => ['enabled' => true],
    ]);
    $stripe->paymentIntents->confirm(
        $paymentIntent->id,
        [
          'payment_method' => 'pm_card_visa',
          'return_url' => 'https://www.example.com',
        ]
    );
    $stripe->charges->create([
        'amount' => 50,
        'currency' => 'usd',
        'source' => 'tok_visa',
    ]);
    
}


/// -------------------------
/// main routin starts here.
/// -------------------------
$userData    = json_decode($_POST['userInfo']);
$status      = processPayment($userData);
echo json_encode($status);
?>
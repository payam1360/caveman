
<?php

require '../../vendor/autoload.php';


function processPayment($data) {

    $stripe = new Stripe\StripeClient('sk_test_51Odb1JGvkwgMtml80Bc0CdBOesMqZzMeulH9j8QO03HnfrLniWn96gEYLK9QdLbmmXQ1voYVKBib06UaTdqxgzfP00P41SGnWu');
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


$userdata  = json_decode($_POST['userInfo']);
$data      = processPayment($userdata);
echo json_encode($data);
?>
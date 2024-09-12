<?php

// admin stripe publishable key 
$AdminStripePublicKey = 'pk_test_51Odb1JGvkwgMtml81N0ajd4C9xKKHD9DhnMhcfyBegjRS8eatgXQdBj1o2fnlpwCcEHOZrJJ7Sd7D0UJqXipzRmQ00CPr9wDNl';
$userInfo        = json_decode($_POST['userInfo']);
// server connect
echo json_encode($AdminStripePublicKey);

?>
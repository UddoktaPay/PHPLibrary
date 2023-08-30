<?php
require_once 'UddoktaPay.php';

$apiKey = "982d381360a69d419689740d9f2e26ce36fb7a50";
$apiBaseURL = "https://sandbox.uddoktapay.com/api/checkout-v2";
$uddoktaPay = new UddoktaPay($apiKey, $apiBaseURL);

// Example request data for initializing a payment
$requestData = [
    'full_name'     => "John Doe",
    'email'         => "test@test.com",
    'amount'        => 10,
    'metadata'      => [
        'example_metadata_key' => "example_metadata_value",
        // ... Add more key-value pairs for dynamic metadata ...
    ],
    'redirect_url'  => 'http://localhost/success.php',
    'return_type'   => 'GET',
    'cancel_url'    => 'http://localhost/cancel.php',
    'webhook_url'   => 'http://localhost/ipn.php',
];

try {
    $paymentUrl = $uddoktaPay->initPayment($requestData);
    header('Location:' . $paymentUrl); // redirect user
} catch (Exception $e) {
    echo "Initialization Error: " . $e->getMessage();
}
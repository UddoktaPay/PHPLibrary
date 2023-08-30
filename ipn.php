<?php
require_once 'UddoktaPay.php';

$apiKey = "your_api_key";
$apiBaseURL = "https://pay.uddoktapay.com/api/checkout-v2";
$uddoktaPay = new UddoktaPay($apiKey, $apiBaseURL);

try {
    $ipnResponse = $uddoktaPay->executePayment();
    
    // Process the IPN response
    if ($ipnResponse['status']) {
        // IPN request was valid, process $ipnResponse data
        // ...
        echo "IPN request successfully processed.";
    } else {
        // Invalid IPN request, handle the error
        echo "IPN request error: " . $ipnResponse['message'];
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
<?php
require_once 'UddoktaPay.php';

$apiKey = "982d381360a69d419689740d9f2e26ce36fb7a50";
$apiBaseURL = "https://sandbox.uddoktapay.com/api/checkout-v2";
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
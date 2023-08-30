<?php
require_once 'UddoktaPay.php';

$apiKey = "982d381360a69d419689740d9f2e26ce36fb7a50"; // API KEY
$apiBaseURL = "https://sandbox.uddoktapay.com/api/checkout-v2"; // API URL
$uddoktaPay = new UddoktaPay($apiKey, $apiBaseURL);

// Simulating getting the invoice ID from payment success page (GET or POST)
$invoiceId = $_REQUEST['invoice_id']; // Assuming you receive it via GET or POST

try {
    // Verify payment
    $response = $uddoktaPay->verifyPayment($invoiceId);

    print_r($response); // Display the verification response
} catch (Exception $e) {
    echo "Verification Error: " . $e->getMessage();
}
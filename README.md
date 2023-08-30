# UddoktaPay PHP Library

![UddoktaPay Logo](https://uddoktapay.com/assets/images/logo.png)

The UddoktaPay PHP Library allows you to seamlessly integrate the UddoktaPay payment gateway into your PHP applications.

## Table of Contents

- [Installation](#installation)
- [Usage](#usage)
  - [Initializing the Library](#initializing-the-library)
  - [Initializing a Payment](#initializing-a-payment)
  - [Verifying a Payment](#verifying-a-payment)
  - [Handling IPN Notifications](#handling-ipn-notifications)
- [Examples](#examples)
- [License](#license)

## Installation

You can include this library in your project by copying the `UddoktaPay.php` file into your project directory.

## Usage

### Initializing the Library

Before using the library, make sure to include the `UddoktaPay.php` file in your script:

```php
require_once 'UddoktaPay.php';
```

### Initializing a Payment

To initiate a payment, follow these steps:

1. Initialize the `UddoktaPay` class with your API key and base URL:

```php
$apiKey = "982d381360a69d419689740d9f2e26ce36fb7a50"; // API KEY
$apiBaseURL = "https://sandbox.uddoktapay.com/api/checkout-v2"; // API URL
$uddoktaPay = new UddoktaPay($apiKey, $apiBaseURL);
```

2. Prepare payment request data and initiate payment:

```php
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
```


## Available API Types

The `initPayment` method allows you to specify the API type as the second parameter. The available options are:

- `checkout`: Basic checkout API (IPN notification only).
- `checkout-v2`: Advanced checkout API (default, Success Page notification only).
- `checkout/global`: Global basic checkout API (IPN notification only).
- `checkout-v2/global`: Global advanced checkout API (Success Page notification only).



### Verifying a Payment

To verify a payment, follow these steps:

1. Initialize the `UddoktaPay` class as shown in the previous steps.

2. Get the invoice ID from the payment success page:

```php
$invoiceId = $_REQUEST['invoice_id']; // Assuming you receive it via GET or POST
```

3. Verify the payment:

```php
try {
    $response = $uddoktaPay->verifyPayment($invoiceId);
    print_r($response); // Display the verification response
} catch (Exception $e) {
    echo "Verification Error: " . $e->getMessage();
}
```

### Handling IPN Notifications (Optional)

To handle IPN (Instant Payment Notification) requests, follow these steps:

1. Initialize the `UddoktaPay` class as shown in the previous steps.

2. Use the `executePayment` method:

```php
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
```



## Notes

- Replace `"your_api_key"` with your actual API key.
- Adjust the request data and other details according to your project requirements.
- The `metadata` field is dynamic; you can add multiple key-value pairs as needed.
- Make sure to handle errors using try-catch blocks as demonstrated above.
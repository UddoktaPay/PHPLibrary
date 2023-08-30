<?php

class UddoktaPay
{
    private $apiKey;
    private $apiBaseURL;

    public function __construct($apiKey, $apiBaseURL)
    {
        $this->apiKey = $apiKey;
        $this->apiBaseURL = $this->normalizeBaseURL($apiBaseURL);
    }

    private function normalizeBaseURL($apiBaseURL)
    {
        $baseURL = rtrim($apiBaseURL, '/');
        $apiSegmentPosition = strpos($baseURL, '/api');

        if ($apiSegmentPosition !== false) {
            $baseURL = substr($baseURL, 0, $apiSegmentPosition + 4); // Include '/api'
        }

        return $baseURL;
    }

    private function buildURL($endpoint)
    {
        $endpoint = ltrim($endpoint, '/');
        return $this->apiBaseURL . '/' . $endpoint;
    }

    public function initPayment($requestData, $apiType = 'checkout-v2')
    {
        $apiUrl = $this->buildURL($apiType);
        $response = $this->sendRequest('POST', $apiUrl, $requestData);

        $this->validateApiResponse($response, 'Payment request failed');
        return $response['payment_url'];
    }

    public function verifyPayment($invoiceId)
    {
        $verifyUrl = $this->buildURL('verify-payment');
        $requestData = ['invoice_id' => $invoiceId];
        return $this->sendRequest('POST', $verifyUrl, $requestData);
    }

    public function executePayment()
    {
        $headerApi = $_SERVER['HTTP_RT_UDDOKTAPAY_API_KEY'] ?? null;
        $this->validateApiHeader($headerApi);

        $rawInput = trim(file_get_contents('php://input'));
        $this->validateIpnResponse($rawInput);

        $data = json_decode($rawInput, true);
        $invoiceId = $data['invoice_id'];

        return $this->verifyPayment($invoiceId);
    }

    private function sendRequest($method, $url, $data)
    {
        $headers = [
            'RT-UDDOKTAPAY-API-KEY: ' . $this->apiKey,
            'accept: application/json',
            'content-type: application/json'
        ];

        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_CUSTOMREQUEST => $method,
            CURLOPT_POSTFIELDS => json_encode($data),
            CURLOPT_HTTPHEADER => $headers,
        ]);

        $response = curl_exec($curl);
        $error = curl_error($curl);
        curl_close($curl);

        if ($error) {
            throw new Exception("cURL Error: $error");
        }

        return json_decode($response, true);
    }

    private function validateApiHeader($headerApi)
    {
        if ($headerApi === null) {
            throw new Exception("Invalid API Key");
        }

        $apiKey = trim($this->apiKey);

        if ($headerApi !== $apiKey) {
            throw new Exception("Unauthorized Action.");
        }
    }

    private function validateApiResponse($response, $errorMessage)
    {
        if (!isset($response['status'], $response['payment_url'])) {
            $message = isset($response['message']) ? $response['message'] : $errorMessage;
            throw new Exception($message);
        }
    }

    private function validateIpnResponse($rawInput)
    {
        if (empty($rawInput)) {
            throw new Exception("Invalid response from UddoktaPay API.");
        }
    }
}
<?php
include 'access_token.php';

// Start output buffering to capture any unwanted output
ob_start();

function generatePassword($shortcode, $passkey, $timestamp) {
    $password = $shortcode . $passkey . $timestamp;
    return base64_encode($password);
}

function initiatePayment($access_token, $shortcode, $password, $timestamp, $amount, $phone_number) {
    $url = "https://sandbox.safaricom.co.ke/mpesa/stkpush/v1/processrequest";

    $headers = array(
        'Authorization: Bearer ' . $access_token,
        'Content-Type: application/json',
    );

    $payload = array(
        "BusinessShortCode" => $shortcode,
        "Password" => $password,
        "Timestamp" => $timestamp,
        "TransactionType" => "CustomerPayBillOnline",
        "Amount" => $amount,
        "PartyA" => $phone_number,
        "PartyB" => $shortcode,
        "PhoneNumber" => $phone_number,
        "CallBackURL" => "https://yourcallbackurl.com/callback", // Update with your actual callback URL
        "AccountReference" => "JamboShop",
        "TransactionDesc" => "Test Payment"
    );

    $ch = curl_init($url);

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));

    $response = curl_exec($ch);

    if (curl_errno($ch)) {
        // cURL error occurred
        return json_encode(['status' => 'error', 'message' => 'Error: ' . curl_error($ch)]);
    } else {
        // Safaricom API response
        $responseData = json_decode($response, true);

        // Check if there's an error in the API response
        if (isset($responseData['errorCode'])) {
            // Return an error message if the response contains an errorCode
            return json_encode(['status' => 'error', 'message' => $responseData['errorMessage']]);
        } else {
            // Return success if there's no errorCode
            return json_encode(['status' => 'success', 'message' => 'Payment initiated successfully']);
        }
    }

    curl_close($ch);
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $shortcode = "174379";
    $passkey = "YOUR_PASS_KEY";
    $timestamp = date('YmdHis');
    $amount = $_POST['amount'];
    $phone_number = $_POST['phone_number'];

    $password = generatePassword($shortcode, $passkey, $timestamp);
    $access_token = getAccessToken();

    if ($access_token) {
        // Ensure nothing else is echoed except JSON response
        $json_response = initiatePayment($access_token, $shortcode, $password, $timestamp, $amount, $phone_number);
    } else {
        $json_response = json_encode(['status' => 'error', 'message' => 'Failed to retrieve access token']);
    }

    // Clean (erase) the output buffer and send only the JSON response
    ob_end_clean();
    echo $json_response;
}
?>

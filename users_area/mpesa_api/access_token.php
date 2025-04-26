<?php

function getAccessToken() {
    $consumer_key = "YOUR_CUSTOMER_KEY";
    $consumer_secret = "YOUR_SECRET_KEY";
    $url = "https://sandbox.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials";

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Authorization: Basic ' . base64_encode($consumer_key . ':' . $consumer_secret)
    ));

    $response = curl_exec($ch);

    if (curl_errno($ch)) {
        echo 'cURL Error: ' . curl_error($ch);
    } else {
        $http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        echo "HTTP Status Code: " . $http_status . "<br>";
        echo "Response: " . $response . "<br>";
        $decoded_response = json_decode($response);
        if (isset($decoded_response->access_token)) {
            return $decoded_response->access_token;
        } else {
            echo "Error retrieving access token. Response: " . $response;
            return null;
        }
    }

    curl_close($ch);
}

// For testing
if (isset($_GET['get_token'])) {
    $token = getAccessToken();
    echo "Access Token: " . $token;
}
?>

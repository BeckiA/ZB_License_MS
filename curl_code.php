<?php

// URL to send the GET request to
$url = "http://localhost/licensesystem/assets/expiration_mailer/notify_expiration.php";

// Initialize cURL session
$ch = curl_init();

// Set cURL options
curl_setopt($ch, CURLOPT_URL, $url);  // Set the URL
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);  // Return the response as a string
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);  // Set timeout for connection
curl_setopt($ch, CURLOPT_TIMEOUT, 10);  // Set overall timeout

// Execute cURL session and fetch the response
$response = curl_exec($ch);

// Check for cURL errors
if (curl_errno($ch)) {
    echo 'cURL error: ' . curl_error($ch);
}

// Close cURL session
curl_close($ch);

// Output the response
echo $response;

?>

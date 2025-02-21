<?php
function isValidPhoneNumber($phone_number, $customer_id, $api_key) {
    // Updated API URL to include the correct endpoint ('standard')
    $api_url = "https://rest-ww.telesign.com/v1/phoneid/standard/$phone_number";
    
    // Adjusted headers: Telesign API returns JSON, so Content-Type should be 'application/json' instead of 'application/x-www-form-urlencoded'
    $headers = [
        "Authorization: Basic " . base64_encode("$customer_id:$api_key"),
        "Content-Type: application/json", // Updated Content-Type header to match the API response format
        "Accept: application/json" // Added Accept header to specify we want a JSON response
    ];
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $api_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    
    // Execute cURL and capture the response
    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    // If the HTTP code is not 200, return false (indicating failure)
    if ($http_code !== 200) {
        return false; // API request failed
    }
    
    // Decode the JSON response from Telesign
    $data = json_decode($response, true);
    
    // Check if the 'phone_type' key exists in the response
    if (!isset($data['numbering']['phone_type'])) {
        return false; // Unexpected API response
    }
    
    // Define valid phone types that should return true
    $valid_types = ["VALID", "FIXED_LINE", "MOBILE"];
    
    // Check if the phone type from the API response is in the valid list
    return in_array(strtoupper($data['numbering']['phone_type']), $valid_types);
}

// Usage example
$phone_number = "1234567890"; // Replace with actual phone number
$customer_id = "your_customer_id"; // Replace with your Telesign customer ID
$api_key = "your_api_key"; // Replace with your Telesign API key
$result = isValidPhoneNumber($phone_number, $customer_id, $api_key);

// Output the result (true if valid, false if invalid)
var_dump($result);

?>

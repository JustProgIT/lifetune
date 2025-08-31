<?php
function getBaziCalculation($dob,$tob,$timezone) {    
	$result = "$dob $tob $timezone";

    $api_url = 'https://api.deepseek.com/v1/chat/completions';
	$api_key = getenv("API_DS");
	
	$prompt = "Analyze Bazi chart for:
    Date of Birth: $dob
    Time of Birth: $tob
    Timezone: $timezone
    Provide 7 topic based on the four pillars without mension about element or bazi words.
	and start with this sentence 'Here’s an analysis of your life chart based on the provided details, structured into seven key topics' and no others comment after topic seventh
	1. Personality
2. Career
3. Wealth
4. Relationships
5. Health
6. Life Challenges
7. Life Purpose";

	$prompt2 = "Calculate Bazi chart for:
    Date of Birth: $dob
    Time of Birth: $tob
    Timezone: $timezone
	give the result with 4 conditions
	1. provide Four Pillars with birthdate birthtime location details
2. no chinese characters or Chinese alphabet
3. no ending note or comment 
4. no drawing table";

    $data = [
        "model" => "deepseek-chat",
        "messages" => [["role" => "user", "content" => $prompt2]]
    ];
    
    // Initialize cURL
    $ch = curl_init();
    
    curl_setopt($ch, CURLOPT_URL, $api_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Authorization: Bearer ' . $api_key
    ]);
    
    // Execute API request
    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    
    if (curl_errno($ch)) {
        return ['error' => 'API connection failed: ' . curl_error($ch)];
    }
    
    curl_close($ch);
    
    // Check for successful response
    if ($http_code !== 200) {
        return ['error' => 'API returned HTTP code ' . $http_code];
    }
    
    // Decode JSON response
    $result = json_decode($response, true);
    
    if (json_last_error() !== JSON_ERROR_NONE) {
        return ['error' => 'Failed to decode API response'];
    }
    
    // Check for API errors
    if (isset($result['error'])) {
        return ['error' => $result['error']];
    }
    
    return $result["choices"][0]["message"]["content"];
}

function getkeys($dob,$tob,$timezone) {    
	$result = "$dob $tob $timezone";
	
    $api_url = 'https://api.deepseek.com/v1/chat/completions';
    $api_key = getenv("API_DS");
	
	$prompt = "Analyze Bazi chart for:
    Date of Birth: $dob
    Time of Birth: $tob
    Timezone: $timezone
    Provide 7 topic based on the four pillars without mension about element or bazi words.
	and start with this sentence 'Here’s an analysis of your life chart based on the provided details, structured into seven key topics' and no others comment after topic seventh
	1. Personality
2. Career
3. Wealth
4. Relationships
5. Health
6. Life Challenges
7. Life Purpose";

	$prompt2 = "Calculate Bazi chart for:
    Date of Birth: $dob
    Time of Birth: $tob
    Timezone: $timezone
	give the result with 4 conditions
	1. provide Four Pillars with birthdate birthtime location details
2. no chinese characters or Chinese alphabet
3. no ending note or comment 
4. no drawing table";

    $data = [
        "model" => "deepseek-chat",
        "messages" => [["role" => "user", "content" => $prompt]]
    ];
    
    // Initialize cURL
    $ch = curl_init();
    
    curl_setopt($ch, CURLOPT_URL, $api_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Authorization: Bearer ' . $api_key
    ]);
    
    // Execute API request
    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    
    if (curl_errno($ch)) {
        return ['error' => 'API connection failed: ' . curl_error($ch)];
    }
    
    curl_close($ch);
    
    // Check for successful response
    if ($http_code !== 200) {
        return ['error' => 'API returned HTTP code ' . $http_code];
    }
    
    // Decode JSON response
    $result = json_decode($response, true);
    
    if (json_last_error() !== JSON_ERROR_NONE) {
        return ['error' => 'Failed to decode API response'];
    }
    
    // Check for API errors
    if (isset($result['error'])) {
        return ['error' => $result['error']];
    }
    
    return $result["choices"][0]["message"]["content"];
}
?>
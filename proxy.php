<?php
// Load WordPress
define('WP_USE_THEMES', false);
require_once('../../../wp-load.php');
// Endpoint URLs
$etherscanUrl = "https://api.etherscan.io/api";
$loopringUrl = "https://api3.loopring.io/api/v3/account";
$loopringNFTUrl = "https://api3.loopring.io/api/v3/user/nft/balances";
// Check if a request was made
// Check if the request came from the plugin
if (!isset($_SERVER['HTTP_X_PROXY_KEY']) || $_SERVER['HTTP_X_PROXY_KEY'] !== $_SESSION['proxy_key']) {
    http_response_code(403); // Forbidden
    exit('Access denied.');
}

if(isset($_GET['url'])) {
    $url = filter_input(INPUT_GET, 'url', FILTER_SANITIZE_URL);
$headers = array('Content-Type: application/json');
 $loopringApiKey = get_option('loopring_api_key');
// Check which endpoint was requested
if (strpos($url, $etherscanUrl) !== false) {
    // Etherscan endpoint requested
    $apiKey = ''; // Put your Etherscan API key here
    $url .= "?apikey=$apiKey";
    $_SESSION['selectedAccount'] = filter_input(INPUT_GET, 'owner', FILTER_SANITIZE_STRING);
} elseif (strpos($url, $loopringUrl) !== false) {
    // Loopring endpoint requested
    $_SESSION['selectedAccount'] = filter_input(INPUT_GET, 'owner', FILTER_SANITIZE_STRING);
    $owner = filter_input(INPUT_GET, 'owner', FILTER_SANITIZE_STRING);
    $url .= "?owner=$owner";
    $headers = array('Content-Type: application/json', "X-API-KEY: $loopringApiKey");
} elseif (strpos($url, $loopringNFTUrl) !== false) {
    // Loopring endpoint requested
    $owner = filter_input(INPUT_GET, 'owner', FILTER_SANITIZE_STRING);
    if(isset($_GET['tokenAddrs'])){
        $token = filter_input(INPUT_GET, 'tokenAddrs', FILTER_SANITIZE_STRING);
        $url .= "?accountId=$owner&tokenAddrs=$token";
    }
    else{
        $url .= "?accountId=$owner";
    }
    
    $_SESSION['selectedAccountId'] = $owner;
    $headers = array('Content-Type: application/json', "X-API-KEY: $loopringApiKey");

    $nfts = array();
    $offset = 0;
    do {
        $url_with_offset = $url . "&offset=$offset";

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_TIMEOUT, 60); // set timeout to 60 seconds
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
        curl_setopt($ch, CURLOPT_URL, $url_with_offset);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode != 200) {
            die('Error retrieving NFTs from Loopring API');
        }

        $json = json_decode($response, true);
        $nfts = array_merge($nfts, $json['data']);
        $offset += count($json['data']);
    } while ($offset < $json['totalNum']);
    return json_encode($nfts);
} else {
    // Invalid endpoint requested
    http_response_code(404);
    echo "Invalid endpoint requested";
    exit;
}
    
    // Create a cURL request to the endpoint
    $ch = curl_init();
	curl_setopt($ch, CURLOPT_TIMEOUT, 60); // set timeout to 60 seconds
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    // Relay the response
    http_response_code($httpCode);
    echo $response;
} else {
    // No request made
    http_response_code(400);
    echo "No request made";
}

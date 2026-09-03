<?php
// test.php
require_once 'ApiClient.php';

// Initialize client pointing to your local server
$client = new ApiClient('http://my-php-server:8000');

echo "--- TESTING GET REQUEST ---\n";
$getResponse = $client->get('api.php');
print_r($getResponse);

echo "\n--- TESTING POST REQUEST ---\n";
$postResponse = $client->post('api.php', ['name' => 'John Doe', 'role' => 'Developer']);
print_r($postResponse);

<?php
// api.php
header("Content-Type: application/json");

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    echo json_encode(["message" => "Hello from local API!", "status" => "success"]);
} elseif ($method === 'POST') {
    $input = json_decode(file_get_contents("php://input"), true);
    echo json_encode(["message" => "Data received successfully", "your_data" => $input]);
} else {
    echo json_encode(["message" => "Method $method handled"]);
}

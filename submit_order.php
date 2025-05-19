<?php
// Set response type to JSON
header("Content-Type: application/json");

// Connect to database (adjust credentials if needed)
$conn = new mysqli("localhost", "root", "", "food_order_db");

// Check connection
if ($conn->connect_error) {
    echo json_encode(["success" => false, "error" => "DB connection failed: " . $conn->connect_error]);
    exit;
}

// Read raw JSON input
$rawData = file_get_contents("php://input");
$data = json_decode($rawData, true);

// Validate JSON input
if (
    isset($data['name']) &&
    isset($data['address']) &&
    isset($data['phone']) &&
    isset($data['cart']) && is_array($data['cart']) &&
    isset($data['total'])
) {
    $name = $conn->real_escape_string($data['name']);
    $address = $conn->real_escape_string($data['address']);
    $phone = $conn->real_escape_string($data['phone']);
    $items = $conn->real_escape_string(json_encode($data['cart']));
    $total = floatval($data['total']);

    $sql = "INSERT INTO orders (customer_name, address, phone, items, total_amount)
            VALUES ('$name', '$address', '$phone', '$items', $total)";

    if ($conn->query($sql) === TRUE) {
        echo json_encode(["success" => true]);
    } else {
        echo json_encode(["success" => false, "error" => $conn->error]);
    }
} else {
    echo json_encode([
        "success" => false,
        "error" => "Invalid data",
        "raw_input" => $rawData
    ]);
}

$conn->close();
?>

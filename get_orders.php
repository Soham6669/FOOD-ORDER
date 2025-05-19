<?php
// Return JSON response
header("Content-Type: application/json");

// Connect to MySQL database
$conn = new mysqli("localhost", "root", "", "food_order_db");

// Check DB connection
if ($conn->connect_error) {
    echo json_encode([
        "success" => false,
        "error" => "DB connection failed: " . $conn->connect_error
    ]);
    exit;
}

// Run query to fetch all orders
$sql = "SELECT * FROM orders ORDER BY order_time DESC";
$result = $conn->query($sql);

if (!$result) {
    echo json_encode([
        "success" => false,
        "error" => "Query failed: " . $conn->error
    ]);
    exit;
}

$orders = [];

// Loop through orders and decode cart JSON
while ($row = $result->fetch_assoc()) {
    $row['items'] = json_decode($row['items'], true);  // decode cart JSON
    $orders[] = $row;
}

// Send back success response with orders
echo json_encode([
    "success" => true,
    "orders" => $orders
]);

$conn->close();
?>

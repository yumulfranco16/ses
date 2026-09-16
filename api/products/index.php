<?php

header("Content-Type: application/json");

require_once "../../config/database.php";

$sql = "SELECT
            p.id,
            p.product_name,
            p.category,
            p.price,
            p.description,
            p.product_image,
            p.sellerID,
            s.fullname AS seller_name
        FROM products p
        INNER JOIN seller s
            ON p.sellerID = s.sellerID
        ORDER BY p.id DESC";

$result = $conn->query($sql);

if (!$result) {

    echo json_encode([
        "success" => false,
        "message" => "Database error: " . $conn->error
    ]);

    exit;
}

$products = [];

while ($row = $result->fetch_assoc()) {
    $products[] = $row;
}

echo json_encode([
    "success" => true,
    "count" => count($products),
    "products" => $products
]);

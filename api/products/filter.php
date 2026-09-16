<?php

header("Content-Type: application/json");

require_once "../../config/database.php";

$category = $_GET['category'] ?? '';

if (empty($category)) {

    echo json_encode([
        "success" => false,
        "message" => "Category is required"
    ]);

    exit;
}

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
        WHERE p.category = ?";

$stmt = $conn->prepare($sql);

$stmt->bind_param("s", $category);

$stmt->execute();

$result = $stmt->get_result();

$products = [];

while ($row = $result->fetch_assoc()) {
    $products[] = $row;
}

echo json_encode([
    "success" => true,
    "count" => count($products),
    "products" => $products
]);
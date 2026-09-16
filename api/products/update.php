<?php

header("Content-Type: application/json");

require_once "../../config/database.php";

$id = $_POST['id'] ?? '';
$product_name = $_POST['product_name'] ?? '';
$category = $_POST['category'] ?? '';
$price = $_POST['price'] ?? '';
$description = $_POST['description'] ?? '';

if (
    empty($id) ||
    empty($product_name) ||
    empty($category) ||
    empty($price)
) {
    echo json_encode([
        "success" => false,
        "message" => "Required fields are missing"
    ]);
    exit;
}

$sql = "UPDATE products
        SET product_name = ?,
            category = ?,
            price = ?,
            description = ?
        WHERE id = ?";

$stmt = $conn->prepare($sql);

$stmt->bind_param(
    "ssdsi",
    $product_name,
    $category,
    $price,
    $description,
    $id
);

if ($stmt->execute()) {

    echo json_encode([
        "success" => true,
        "message" => "Product updated successfully"
    ]);

} else {

    echo json_encode([
        "success" => false,
        "message" => "Failed to update product"
    ]);
}
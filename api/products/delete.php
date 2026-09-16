<?php

header("Content-Type: application/json");

require_once "../../config/database.php";

$id = $_POST['id'] ?? '';

if (empty($id)) {

    echo json_encode([
        "success" => false,
        "message" => "Product ID is required"
    ]);

    exit;
}

$sql = "DELETE FROM products WHERE id = ?";

$stmt = $conn->prepare($sql);

$stmt->bind_param("i", $id);

if ($stmt->execute()) {

    echo json_encode([
        "success" => true,
        "message" => "Product deleted successfully"
    ]);

} else {

    echo json_encode([
        "success" => false,
        "message" => "Failed to delete product"
    ]);
}
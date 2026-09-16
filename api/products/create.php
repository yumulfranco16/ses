<?php

header("Content-Type: application/json");

require_once "../../config/database.php";

$product_name = $_POST['product_name'] ?? '';
$category = $_POST['category'] ?? '';
$price = $_POST['price'] ?? '';
$description = $_POST['description'] ?? '';
$sellerID = $_POST['sellerID'] ?? '';


if (
    empty($product_name) ||
    empty($category) ||
    empty($price) ||
    empty($sellerID)
) {
    echo json_encode([
        "success" => false,
        "message" => "Product name, category, price and seller are required"
    ]);

    exit;
}



if (!isset($_FILES['product_image'])) {

    echo json_encode([
        "success" => false,
        "message" => "Product image is required"
    ]);

    exit;
}

$file = $_FILES['product_image'];



if ($file['error'] != UPLOAD_ERR_OK) {

    echo json_encode([
        "success" => false,
        "message" => "Failed to upload product image"
    ]);

    exit;
}



$allowed_types = [
    "image/jpeg",
    "image/png",
    "image/gif"
];

if (!in_array($file['type'], $allowed_types)) {

    echo json_encode([
        "success" => false,
        "message" => "Only JPG, PNG and GIF images are allowed"
    ]);

    exit;
}



if ($file['size'] > 2 * 1024 * 1024) {

    echo json_encode([
        "success" => false,
        "message" => "Product image must not exceed 2MB"
    ]);

    exit;
}



$upload_dir = "../../uploads/products/";

if (!is_dir($upload_dir)) {
    mkdir($upload_dir, 0777, true);
}



$file_extension = pathinfo(
    $file['name'],
    PATHINFO_EXTENSION
);

$new_filename = uniqid("product_", true)
              . "."
              . strtolower($file_extension);

$file_path = $upload_dir . $new_filename;



if (!move_uploaded_file(
    $file['tmp_name'],
    $file_path
)) {

    echo json_encode([
        "success" => false,
        "message" => "Failed to save product image"
    ]);

    exit;
}



$sql = "INSERT INTO products
        (
            product_name,
            category,
            price,
            description,
            product_image,
            sellerID
        )
        VALUES (?, ?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);

$stmt->bind_param(
    "ssdssi",
    $product_name,
    $category,
    $price,
    $description,
    $new_filename,
    $sellerID
);



if ($stmt->execute()) {

    echo json_encode([
        "success" => true,
        "message" => "Product added successfully",
        "product_image" => $new_filename
    ]);

} else {



    if (file_exists($file_path)) {
        unlink($file_path);
    }

    echo json_encode([
        "success" => false,
        "message" => "Failed to add product: " . $stmt->error
    ]);
}

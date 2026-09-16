<?php

session_start();

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

require_once "config/database.php";



$id = $_POST['id'] ?? 0;
$product_name = $_POST['product_name'] ?? '';
$category = $_POST['category'] ?? '';
$price = $_POST['price'] ?? '';
$description = $_POST['description'] ?? '';



if ($id <= 0) {
    die("Invalid product ID.");
}



if (
    isset($_FILES['product_image']) &&
    $_FILES['product_image']['error'] == UPLOAD_ERR_OK
) {

    $file = $_FILES['product_image'];



    $allowed_types = [
        "image/jpeg",
        "image/png",
        "image/gif"
    ];

    if (!in_array($file['type'], $allowed_types)) {
        die("Only JPG, PNG and GIF images are allowed.");
    }



    if ($file['size'] > 2 * 1024 * 1024) {
        die("Image must not exceed 2MB.");
    }


    $upload_dir = "uploads/products/";

    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }



    $extension = pathinfo(
        $file['name'],
        PATHINFO_EXTENSION
    );

    $new_filename =
        uniqid("product_", true)
        . "."
        . strtolower($extension);

    $file_path =
        $upload_dir . $new_filename;


    if (!move_uploaded_file(
        $file['tmp_name'],
        $file_path
    )) {

        die("Failed to upload image.");
    }



    $sql = "UPDATE products
            SET product_name = ?,
                category = ?,
                price = ?,
                description = ?,
                product_image = ?
            WHERE id = ?";

    $stmt = $conn->prepare($sql);

    $stmt->bind_param(
        "ssdssi",
        $product_name,
        $category,
        $price,
        $description,
        $new_filename,
        $id
    );

} else {



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
}



if ($stmt->execute()) {

    echo "<h2>Product Updated Successfully</h2>";

    echo '<a href="products.php">';
    echo 'Back to Products';
    echo '</a>';

} else {

    echo "<h2>Update Failed</h2>";

    echo "<p>";
    echo htmlspecialchars($stmt->error);
    echo "</p>";

    echo '<a href="products.php">';
    echo 'Back to Products';
    echo '</a>';
}
```php
<?php

session_start();

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

$product_name = $_POST['product_name'] ?? '';
$category = $_POST['category'] ?? '';
$price = $_POST['price'] ?? '';
$description = $_POST['description'] ?? '';
$sellerID = $_POST['sellerID'] ?? '';



$url = "http://localhost/shopnow/api/products/create.php";



if (!isset($_FILES['product_image'])) {
    die("Product image is required.");
}

$file = $_FILES['product_image'];



$post_data = [
    "product_name" => $product_name,
    "category" => $category,
    "price" => $price,
    "description" => $description,
    "sellerID" => $sellerID,

    "product_image" => new CURLFile(
        $file['tmp_name'],
        $file['type'],
        $file['name']
    )
];



$curl = curl_init();

curl_setopt($curl, CURLOPT_URL, $url);
curl_setopt($curl, CURLOPT_POST, true);
curl_setopt($curl, CURLOPT_POSTFIELDS, $post_data);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

$response = curl_exec($curl);



if ($response === false) {

    die(
        "Unable to connect to product API: "
        . curl_error($curl)
    );
}

curl_close($curl);



$result = json_decode($response, true);



if (!is_array($result)) {

    die("Invalid response from product API.");
}



if ($result['success'] === true) {

    echo "<h2>Product Added Successfully</h2>";

    echo "<p>"
        . htmlspecialchars($result['message'])
        . "</p>";

    echo '<p>';
    echo '<a href="products.php">View Products</a>';
    echo '</p>';

} else {

    echo "<h2>Failed to Add Product</h2>";

    echo "<p>"
        . htmlspecialchars($result['message'])
        . "</p>";

    echo '<p>';
    echo '<a href="product_create.php">Back to Add Product</a>';
    echo '</p>';
}
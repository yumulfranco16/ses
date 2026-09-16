```php
<?php
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

if ($_SESSION['user_type'] != "seller") {
    die("Only sellers can delete products.");
}

require_once "config/database.php";

$id = $_GET['id'] ?? 0;

if ($id <= 0) {
    die("Invalid product ID.");
}

// Get the product first
$sql = "SELECT * FROM products WHERE id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();

$result = $stmt->get_result();
$product = $result->fetch_assoc();

if (!$product) {
    die("Product not found.");
}

// Delete product
$sql = "DELETE FROM products WHERE id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);

if ($stmt->execute()) {

    // Delete product image if it exists
    if (!empty($product['product_image'])) {

        $image_path = "uploads/products/" . $product['product_image'];

        if (file_exists($image_path)) {
            unlink($image_path);
        }
    }

    echo "<h2>Product Deleted Successfully</h2>";
    echo '<a href="products.php">Back to Products</a>';

} else {

    echo "<h2>Delete Failed</h2>";
    echo "<p>" . htmlspecialchars($stmt->error) . "</p>";
    echo '<a href="products.php">Back to Products</a>';
}
?>
```

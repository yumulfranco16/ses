<?php

session_start();

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

if ($_SESSION['user_type'] != "seller") {
    die("Only sellers can edit products.");
}

require_once "config/database.php";



$id = $_GET['id'] ?? 0;

if ($id <= 0) {
    die("Invalid product ID.");
}



$sql = "SELECT *
        FROM products
        WHERE id = ?";

$stmt = $conn->prepare($sql);

$stmt->bind_param("i", $id);

$stmt->execute();

$result = $stmt->get_result();

$product = $result->fetch_assoc();

if (!$product) {
    die("Product not found.");
}

?>

<!DOCTYPE html>
<html>

<head>

    <title>Edit Product</title>

</head>

<body>

<h1>Edit Product</h1>

<form
    action="product_edit_process.php"
    method="POST"
    enctype="multipart/form-data"
>

    <!-- Product ID -->

    <input
        type="hidden"
        name="id"
        value="<?php echo $product['id']; ?>"
    >

    <p>

        <label>Product Name:</label><br>

        <input
            type="text"
            name="product_name"
            value="<?php
                echo htmlspecialchars(
                    $product['product_name']
                );
            ?>"
            required
        >

    </p>

    <p>

        <label>Category:</label><br>

        <input
            type="text"
            name="category"
            value="<?php
                echo htmlspecialchars(
                    $product['category']
                );
            ?>"
            required
        >

    </p>

    <p>

        <label>Price:</label><br>

        <input
            type="number"
            name="price"
            step="0.01"
            min="0"
            value="<?php
                echo htmlspecialchars(
                    $product['price']
                );
            ?>"
            required
        >

    </p>

    <p>

        <label>Description:</label><br>

        <textarea
            name="description"
            rows="5"
            cols="40"
        ><?php
            echo htmlspecialchars(
                $product['description']
            );
        ?></textarea>

    </p>

    <p>

        <label>Current Image:</label><br>

        <?php if (!empty($product['product_image'])): ?>

            <img
                src="uploads/products/<?php
                    echo htmlspecialchars(
                        $product['product_image']
                    );
                ?>"
                width="150"
                height="150"
                alt="Product Image"
            >

        <?php else: ?>

            No image

        <?php endif; ?>

    </p>

    <p>

        <label>New Image:</label><br>

        <input
            type="file"
            name="product_image"
            accept="image/jpeg,image/png,image/gif"
        >

    </p>

    <button type="submit">
        Update Product
    </button>

</form>

<br>

<a href="products.php">
    Back to Products
</a>

</body>

</html>

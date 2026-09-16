<?php

session_start();

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}


$url = "http://localhost/shopnow/api/products/index.php";

$response = file_get_contents($url);

if ($response === false) {
    die("Unable to connect to products API.");
}

$result = json_decode($response, true);

if (!is_array($result)) {
    die("Invalid response from products API.");
}

if ($result['success'] !== true) {
    die(
        "Error: " .
        htmlspecialchars($result['message'])
    );
}

$products = $result['products'];

?>

<!DOCTYPE html>
<html>

<head>
    <title>Products</title>
</head>

<body>

<h1>Products</h1>

<a href="dashboard.php">Dashboard</a>

<?php if ($_SESSION['user_type'] == "seller"): ?>

    |
    <a href="product_create.php">
        Add Product
    </a>

<?php endif; ?>

<hr>

<?php if (count($products) > 0): ?>

<table border="1" cellpadding="8">

    <tr>

        <th>Image</th>
        <th>ID</th>
        <th>Product</th>
        <th>Category</th>
        <th>Price</th>
        <th>Description</th>
        <th>Seller</th>

        <?php if ($_SESSION['user_type'] == "seller"): ?>
            <th>Action</th>
        <?php endif; ?>

    </tr>

    <?php foreach ($products as $product): ?>

    <tr>

        <td>

            <?php if (!empty($product['product_image'])): ?>

                <img
                    src="uploads/products/<?php
                        echo htmlspecialchars(
                            $product['product_image']
                        );
                    ?>"
                    width="100"
                    height="100"
                    alt="Product Image"
                >

            <?php else: ?>

                No image

            <?php endif; ?>

        </td>

        <td>
            <?php echo htmlspecialchars($product['id']); ?>
        </td>

        <td>
            <?php echo htmlspecialchars($product['product_name']); ?>
        </td>

        <td>
            <?php echo htmlspecialchars($product['category']); ?>
        </td>

        <td>
            ₱<?php echo htmlspecialchars($product['price']); ?>
        </td>

        <td>
            <?php echo htmlspecialchars($product['description']); ?>
        </td>

        <td>
            <?php echo htmlspecialchars($product['seller_name']); ?>
        </td>

        <?php if ($_SESSION['user_type'] == "seller"): ?>

        <td>

            <a href="product_edit.php?id=<?php
                echo $product['id'];
            ?>">
                Edit
            </a>

            |

            <a href="product_delete.php?id=<?php
                echo $product['id'];
            ?>"
               onclick="return confirm('Delete this product?');">
                Delete
            </a>

        </td>

        <?php endif; ?>

    </tr>

    <?php endforeach; ?>

</table>

<?php else: ?>

    <p>No products found.</p>

<?php endif; ?>

</body>

</html>

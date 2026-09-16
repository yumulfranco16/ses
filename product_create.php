<?php

session_start();

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

if ($_SESSION['user_type'] != "seller") {
    die("Only sellers can add products.");
}

$user = $_SESSION['user'];

?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Product</title>
</head>

<body>

<h1>Add Product</h1>

<form action="product_create_process.php"
      method="POST"
      enctype="multipart/form-data">

    <input
        type="hidden"
        name="sellerID"
        value="<?php echo htmlspecialchars($user['sellerID']); ?>"
    >

    <p>
        <label>Product Name:</label><br>

        <input
            type="text"
            name="product_name"
            required
        >
    </p>

    <p>
        <label>Category:</label><br>

        <input
            type="text"
            name="category"
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
            required
        >
    </p>

    <p>
        <label>Description:</label><br>

        <textarea
            name="description"
            rows="5"
            cols="40"
        ></textarea>
    </p>

    <p>
        <label>Product Image:</label><br>

        <input
            type="file"
            name="product_image"
            accept="image/*"
            required
        >
    </p>

    <button type="submit">
        Add Product
    </button>

</form>

<br>

<a href="products.php">Back to Products</a>

</body>
</html>


<?php

session_start();

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

$user = $_SESSION['user'];

?>

<!DOCTYPE html>
<html>

<head>
    <title>Dashboard</title>
</head>

<body>

<h1>Dashboard</h1>

<hr>

<!-- Profile Picture -->

<?php if (!empty($user['profile_pic'])): ?>

    <img
        src="uploads/profile/<?php echo htmlspecialchars($user['profile_pic']); ?>"
        width="120"
        height="120"
        alt="Profile Picture"
    >

<?php else: ?>

    <p>No profile picture.</p>

<?php endif; ?>

<br><br>

<!-- User Information -->

<h2>
    Welcome,
    <?php echo htmlspecialchars($user['fullname']); ?>!
</h2>

<p>
    <strong>Username:</strong>
    <?php echo htmlspecialchars($user['username']); ?>
</p>

<p>
    <strong>Email:</strong>
    <?php echo htmlspecialchars($user['email']); ?>
</p>

<p>
    <strong>User Type:</strong>
    <?php echo htmlspecialchars($_SESSION['user_type']); ?>
</p>

<hr>

<!-- Navigation -->

<a href="products.php">
    View Products
</a>

<?php if ($_SESSION['user_type'] == "seller"): ?>

    |
    <a href="product_create.php">
        Add Product
    </a>

<?php endif; ?>

|

<a href="profile.php">
    My Profile
</a>

|

<a href="logout.php">
    Logout
</a>

</body>

</html>

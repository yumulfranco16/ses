<?php

session_start();

if (isset($_SESSION['user'])) {
    header("Location: dashboard.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
</head>
<body>

<h1>Product Management System</h1>

<h2>Login</h2>

<form action="login_process.php" method="POST">

    <label>Username:</label><br>
    <input type="text" name="username" required>
    <br><br>

    <label>Password:</label><br>
    <input type="password" name="password" required>
    <br><br>

    <label>User Type:</label><br>

    <select name="user_type" required>
        <option value="">Select User Type</option>
        <option value="seller">Seller</option>
        <option value="customer">Customer</option>
    </select>

    <br><br>

    <button type="submit">Login</button>

</form>

<p>
    Don't have an account?
    <a href="register.php">Register</a>
</p>

</body>
</html>
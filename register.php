<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
</head>

<body>

<h1>Product Management System</h1>

<h2>Register</h2>

<form action="api/register.php" method="POST" enctype="multipart/form-data">

    <label>Username:</label><br>
    <input type="text" name="username" required>

    <br><br>

    <label>Full Name:</label><br>
    <input type="text" name="fullname" required>

    <br><br>

    <label>Email:</label><br>
    <input type="email" name="email" required>

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

    <label>Profile Picture:</label><br>
    <input
        type="file"
        name="profile_pic"
        accept="image/*"
        required
    >

    <br><br>

    <button type="submit">
        Register
    </button>

</form>

<p>
    Already have an account?
    <a href="login.php">Login</a>
</p>

</body>
</html>


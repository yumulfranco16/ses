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
    <title>My Profile</title>
</head>

<body>

<h1>My Profile</h1>

<!-- Current Profile Picture -->

<?php if (!empty($user['profile_pic'])): ?>

    <img
        src="uploads/profile/<?php echo htmlspecialchars($user['profile_pic']); ?>"
        width="150"
        height="150"
        alt="Profile Picture"
    >

<?php else: ?>

    <p>No profile picture.</p>

<?php endif; ?>

<hr>

<form
    action="profile_update.php"
    method="POST"
    enctype="multipart/form-data"
>

    <p>
        <strong>Username:</strong><br>

        <?php echo htmlspecialchars($user['username']); ?>
    </p>

    <p>
        <label>Full Name:</label><br>

        <input
            type="text"
            name="fullname"
            value="<?php echo htmlspecialchars($user['fullname']); ?>"
            required
        >
    </p>

    <p>
        <label>Email:</label><br>

        <input
            type="email"
            name="email"
            value="<?php echo htmlspecialchars($user['email']); ?>"
            required
        >
    </p>

    <p>
        <label>New Password:</label><br>

        <input
            type="password"
            name="password"
            placeholder="Leave blank to keep current password"
        >
    </p>

    <p>
        <label>Change Profile Picture:</label><br>

        <input
            type="file"
            name="profile_pic"
            accept="image/jpeg,image/png,image/gif"
        >
    </p>

    <button type="submit">
        Update Profile
    </button>

</form>

<hr>

<a href="dashboard.php">Dashboard</a> |

<a href="products.php">Products</a> |

<a href="logout.php">Logout</a>

</body>

</html>

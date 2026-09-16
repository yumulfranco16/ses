<?php

session_start();

$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';
$user_type = $_POST['user_type'] ?? '';

$url = "http://localhost/shopnow/api/login.php";

$data = [
    "username" => $username,
    "password" => $password,
    "user_type" => $user_type
];

$options = [
    "http" => [
        "method" => "POST",
        "header" => "Content-Type: application/x-www-form-urlencoded",
        "content" => http_build_query($data)
    ]
];

$context = stream_context_create($options);

$response = file_get_contents($url, false, $context);

if ($response === false) {
    die("Unable to connect to login API.");
}

$result = json_decode($response, true);

if (!$result) {
    die("Invalid response from login API.");
}

if ($result['success']) {

    $_SESSION['user'] = $result['user'];
    $_SESSION['user_type'] = $user_type;

    header("Location: dashboard.php");
    exit;

} else {

    echo "<h2>Login Failed</h2>";
    echo "<p>" . htmlspecialchars($result['message']) . "</p>";
    echo '<a href="login.php">Back to Login</a>';
}
<?php

header("Content-Type: application/json");

require_once "../config/database.php";

$username = $_POST['username'] ?? '';
$fullname = $_POST['fullname'] ?? '';
$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';
$user_type = $_POST['user_type'] ?? '';

if (
    empty($username) ||
    empty($fullname) ||
    empty($email) ||
    empty($password) ||
    empty($user_type)
) {
    echo json_encode([
        "success" => false,
        "message" => "All fields are required"
    ]);
    exit;
}

$hashed_password = password_hash($password, PASSWORD_DEFAULT);

if ($user_type == "seller") {

    $sql = "INSERT INTO seller
            (username, fullname, email, password)
            VALUES (?, ?, ?, ?)";

} elseif ($user_type == "customer") {

    $sql = "INSERT INTO customer
            (username, fullname, email, password)
            VALUES (?, ?, ?, ?)";

} else {

    echo json_encode([
        "success" => false,
        "message" => "Invalid user type"
    ]);
    exit;
}

$stmt = $conn->prepare($sql);

$stmt->bind_param(
    "ssss",
    $username,
    $fullname,
    $email,
    $hashed_password
);

if ($stmt->execute()) {

    echo json_encode([
        "success" => true,
        "message" => "User created successfully"
    ]);

} else {

    echo json_encode([
        "success" => false,
        "message" => "Failed to create user"
    ]);
}
<?php

header("Content-Type: application/json");

require_once "../config/database.php";

$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';
$user_type = $_POST['user_type'] ?? '';

if (
    empty($username) ||
    empty($password) ||
    empty($user_type)
) {
    echo json_encode([
        "success" => false,
        "message" => "Username, password and user type are required"
    ]);

    exit;
}



if ($user_type == "seller") {

    $sql = "SELECT
                sellerID,
                username,
                fullname,
                email,
                password,
                profile_pic
            FROM seller
            WHERE username = ?";

}



elseif ($user_type == "customer") {

    $sql = "SELECT
                cust_id,
                username,
                fullname,
                email,
                password,
                profile_pic
            FROM customer
            WHERE username = ?";

}

else {

    echo json_encode([
        "success" => false,
        "message" => "Invalid user type"
    ]);

    exit;
}



$stmt = $conn->prepare($sql);

$stmt->bind_param(
    "s",
    $username
);

$stmt->execute();

$result = $stmt->get_result();



if ($result->num_rows == 0) {

    echo json_encode([
        "success" => false,
        "message" => "User not found"
    ]);

    exit;
}

$user = $result->fetch_assoc();



if (!password_verify($password, $user['password'])) {

    echo json_encode([
        "success" => false,
        "message" => "Incorrect password"
    ]);

    exit;
}



unset($user['password']);



echo json_encode([
    "success" => true,
    "message" => "Login successful",
    "user" => $user
]);

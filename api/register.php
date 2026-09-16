```php
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



if (
    $user_type != "customer" &&
    $user_type != "seller"
) {

    echo json_encode([
        "success" => false,
        "message" => "Invalid user type"
    ]);

    exit;
}



if (!isset($_FILES['profile_pic'])) {

    echo json_encode([
        "success" => false,
        "message" => "Profile picture is required"
    ]);

    exit;
}

$file = $_FILES['profile_pic'];



if ($file['error'] != UPLOAD_ERR_OK) {

    echo json_encode([
        "success" => false,
        "message" => "Failed to upload profile picture"
    ]);

    exit;
}



$allowed_types = [
    "image/jpeg",
    "image/png",
    "image/gif"
];

if (!in_array($file['type'], $allowed_types)) {

    echo json_encode([
        "success" => false,
        "message" => "Only JPG, PNG and GIF images are allowed"
    ]);

    exit;
}


if ($file['size'] > 2 * 1024 * 1024) {

    echo json_encode([
        "success" => false,
        "message" => "Profile picture must not exceed 2MB"
    ]);

    exit;
}



$upload_dir = "../uploads/profile/";

if (!is_dir($upload_dir)) {
    mkdir($upload_dir, 0777, true);
}



$file_extension = pathinfo(
    $file['name'],
    PATHINFO_EXTENSION
);

$new_filename = uniqid("profile_", true)
              . "."
              . strtolower($file_extension);

$file_path = $upload_dir . $new_filename;



if (!move_uploaded_file(
    $file['tmp_name'],
    $file_path
)) {

    echo json_encode([
        "success" => false,
        "message" => "Failed to save profile picture"
    ]);

    exit;
}



$hashed_password = password_hash(
    $password,
    PASSWORD_DEFAULT
);


if ($user_type == "customer") {

    $sql = "INSERT INTO customer
            (
                username,
                fullname,
                email,
                password,
                profile_pic
            )
            VALUES (?, ?, ?, ?, ?)";

} else {

    $sql = "INSERT INTO seller
            (
                username,
                fullname,
                email,
                password,
                profile_pic
            )
            VALUES (?, ?, ?, ?, ?)";
}


$stmt = $conn->prepare($sql);

$stmt->bind_param(
    "sssss",
    $username,
    $fullname,
    $email,
    $hashed_password,
    $new_filename
);


if ($stmt->execute()) {

    echo json_encode([
        "success" => true,
        "message" => "Registration successful",
        "profile_pic" => $new_filename
    ]);

} else {

    /*
     * Delete uploaded picture if database
     * insertion failed.
     */

    if (file_exists($file_path)) {
        unlink($file_path);
    }

    echo json_encode([
        "success" => false,
        "message" => "Registration failed: " . $stmt->error
    ]);
}
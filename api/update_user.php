<?php

header("Content-Type: application/json");

require_once "../config/database.php";

/*
|--------------------------------------------------------------------------
| Get form data
|--------------------------------------------------------------------------
*/

$user_id = $_POST['user_id'] ?? '';
$user_type = $_POST['user_type'] ?? '';
$fullname = $_POST['fullname'] ?? '';
$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';

/*
|--------------------------------------------------------------------------
| Check required fields
|--------------------------------------------------------------------------
*/

if (
    empty($user_id) ||
    empty($user_type) ||
    empty($fullname) ||
    empty($email)
) {

    echo json_encode([
        "success" => false,
        "message" => "User ID, user type, fullname and email are required"
    ]);

    exit;
}

/*
|--------------------------------------------------------------------------
| Check user type
|--------------------------------------------------------------------------
*/

if (
    $user_type != "seller" &&
    $user_type != "customer"
) {

    echo json_encode([
        "success" => false,
        "message" => "Invalid user type"
    ]);

    exit;
}

/*
|--------------------------------------------------------------------------
| Get new profile picture
|--------------------------------------------------------------------------
*/

$new_filename = '';

if (
    isset($_FILES['profile_pic']) &&
    $_FILES['profile_pic']['error'] == UPLOAD_ERR_OK
) {

    $file = $_FILES['profile_pic'];

    /*
    | Allowed image types
    */

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

    /*
    | Maximum size: 2MB
    */

    if ($file['size'] > 2 * 1024 * 1024) {

        echo json_encode([
            "success" => false,
            "message" => "Profile picture must not exceed 2MB"
        ]);

        exit;
    }

    /*
    | Create upload folder
    */

    $upload_dir = "../uploads/profile/";

    if (!is_dir($upload_dir)) {

        mkdir(
            $upload_dir,
            0777,
            true
        );
    }

    /*
    | Create unique filename
    */

    $extension = pathinfo(
        $file['name'],
        PATHINFO_EXTENSION
    );

    $new_filename =
        uniqid("profile_", true)
        . "."
        . strtolower($extension);

    $file_path =
        $upload_dir
        . $new_filename;

    /*
    | Move uploaded image
    */

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
}

/*
|--------------------------------------------------------------------------
| Update Seller
|--------------------------------------------------------------------------
*/

if ($user_type == "seller") {

    if (!empty($new_filename)) {

        if (!empty($password)) {

            $hashed_password =
                password_hash(
                    $password,
                    PASSWORD_DEFAULT
                );

            $sql = "UPDATE seller
                    SET fullname = ?,
                        email = ?,
                        password = ?,
                        profile_pic = ?
                    WHERE sellerID = ?";

            $stmt = $conn->prepare($sql);

            $stmt->bind_param(
                "ssssi",
                $fullname,
                $email,
                $hashed_password,
                $new_filename,
                $user_id
            );

        } else {

            $sql = "UPDATE seller
                    SET fullname = ?,
                        email = ?,
                        profile_pic = ?
                    WHERE sellerID = ?";

            $stmt = $conn->prepare($sql);

            $stmt->bind_param(
                "sssi",
                $fullname,
                $email,
                $new_filename,
                $user_id
            );
        }

    } else {

        if (!empty($password)) {

            $hashed_password =
                password_hash(
                    $password,
                    PASSWORD_DEFAULT
                );

            $sql = "UPDATE seller
                    SET fullname = ?,
                        email = ?,
                        password = ?
                    WHERE sellerID = ?";

            $stmt = $conn->prepare($sql);

            $stmt->bind_param(
                "sssi",
                $fullname,
                $email,
                $hashed_password,
                $user_id
            );

        } else {

            $sql = "UPDATE seller
                    SET fullname = ?,
                        email = ?
                    WHERE sellerID = ?";

            $stmt = $conn->prepare($sql);

            $stmt->bind_param(
                "ssi",
                $fullname,
                $email,
                $user_id
            );
        }
    }
}

/*
|--------------------------------------------------------------------------
| Update Customer
|--------------------------------------------------------------------------
*/

else {

    if (!empty($new_filename)) {

        if (!empty($password)) {

            $hashed_password =
                password_hash(
                    $password,
                    PASSWORD_DEFAULT
                );

            $sql = "UPDATE customer
                    SET fullname = ?,
                        email = ?,
                        password = ?,
                        profile_pic = ?
                    WHERE cust_id = ?";

            $stmt = $conn->prepare($sql);

            $stmt->bind_param(
                "ssssi",
                $fullname,
                $email,
                $hashed_password,
                $new_filename,
                $user_id
            );

        } else {

            $sql = "UPDATE customer
                    SET fullname = ?,
                        email = ?,
                        profile_pic = ?
                    WHERE cust_id = ?";

            $stmt = $conn->prepare($sql);

            $stmt->bind_param(
                "sssi",
                $fullname,
                $email,
                $new_filename,
                $user_id
            );
        }

    } else {

        if (!empty($password)) {

            $hashed_password =
                password_hash(
                    $password,
                    PASSWORD_DEFAULT
                );

            $sql = "UPDATE customer
                    SET fullname = ?,
                        email = ?,
                        password = ?
                    WHERE cust_id = ?";

            $stmt = $conn->prepare($sql);

            $stmt->bind_param(
                "sssi",
                $fullname,
                $email,
                $hashed_password,
                $user_id
            );

        } else {

            $sql = "UPDATE customer
                    SET fullname = ?,
                        email = ?
                    WHERE cust_id = ?";

            $stmt = $conn->prepare($sql);

            $stmt->bind_param(
                "ssi",
                $fullname,
                $email,
                $user_id
            );
        }
    }
}

/*
|--------------------------------------------------------------------------
| Check SQL statement
|--------------------------------------------------------------------------
*/

if (!$stmt) {

    echo json_encode([
        "success" => false,
        "message" => "Failed to prepare SQL: "
                   . $conn->error
    ]);

    exit;
}

/*
|--------------------------------------------------------------------------
| Execute
|--------------------------------------------------------------------------
*/

if ($stmt->execute()) {

    echo json_encode([
        "success" => true,
        "message" => "Profile updated successfully",
        "profile_pic" => $new_filename
    ]);

} else {

    echo json_encode([
        "success" => false,
        "message" => "Update failed: "
                   . $stmt->error
    ]);
}
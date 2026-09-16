<?php

session_start();

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

$user = $_SESSION['user'];

$user_type = $_SESSION['user_type'];



if ($user_type == "seller") {

    $user_id = $user['sellerID'];

} else {

    $user_id = $user['cust_id'];
}



$fullname = $_POST['fullname'] ?? '';
$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';



$url = "http://localhost/shopnow/api/update_user.php";



$post_data = [
    "user_id" => $user_id,
    "user_type" => $user_type,
    "fullname" => $fullname,
    "email" => $email,
    "password" => $password
];



if (
    isset($_FILES['profile_pic']) &&
    $_FILES['profile_pic']['error'] == UPLOAD_ERR_OK
) {

    $file = $_FILES['profile_pic'];

    $post_data['profile_pic'] = new CURLFile(
        $file['tmp_name'],
        $file['type'],
        $file['name']
    );
}



$curl = curl_init();

curl_setopt($curl, CURLOPT_URL, $url);

curl_setopt($curl, CURLOPT_POST, true);

curl_setopt(
    $curl,
    CURLOPT_POSTFIELDS,
    $post_data
);

curl_setopt(
    $curl,
    CURLOPT_RETURNTRANSFER,
    true
);

$response = curl_exec($curl);


if ($response === false) {

    die(
        "Unable to connect to update user API: "
        . curl_error($curl)
    );
}

curl_close($curl);



$result = json_decode($response, true);



if (!is_array($result)) {

    echo "<h3>Invalid API Response</h3>";

    echo "<pre>";
    echo htmlspecialchars($response);
    echo "</pre>";

    exit;
}


if ($result['success'] === true) {

    /*
    | Update session information
    */

    $_SESSION['user']['fullname'] = $fullname;
    $_SESSION['user']['email'] = $email;

    /*
    | Update profile picture in session
    */

    if (!empty($result['profile_pic'])) {

        $_SESSION['user']['profile_pic']
            = $result['profile_pic'];
    }

    echo "<h2>Profile Updated Successfully</h2>";

    echo '<p>';
    echo htmlspecialchars($result['message']);
    echo '</p>';

    echo '<a href="profile.php">Back to Profile</a>';

} else {

    echo "<h2>Update Failed</h2>";

    echo "<p>";

    echo htmlspecialchars(
        $result['message'] ?? "Unknown error"
    );

    echo "</p>";

    echo '<a href="profile.php">Back to Profile</a>';
}
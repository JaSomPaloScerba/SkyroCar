$client->setClientId('YOUR_CLIENT_ID_HERE');
$client->setClientSecret('YOUR_CLIENT_SECRET_HERE');
$client->setRedirectUri('http://localhost/google-oauth-app/login.php');




<?php
require_once 'vendor/autoload.php';

// client kod a secret
$client = new Google_Client();
$client->setClientId('YOUR_CLIENT_ID');
$client->setClientSecret('YOUR_CLIENT_SECRET');
$client->setRedirectUri('http://localhost/my_api/login.php');
$client->addScope('email');
$client->addScope('profile');


if (!isset($_GET['code'])) {
    $auth_url = $client->createAuthUrl();
    header('Location: ' . filter_var($auth_url, FILTER_SANITIZE_URL));
    exit;
} else {
    $client->authenticate($_GET['code']);
    $token = $client->getAccessToken();
    $client->setAccessToken($token);

    $oauth = new Google_Service_Oauth2($client);
    $userinfo = $oauth->userinfo->get();

    // Store user in DB
    $conn = new mysqli("localhost", "root", "root", "oauth_db"); // default MAMP credentials

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $google_id = $conn->real_escape_string($userinfo->id);
    $name = $conn->real_escape_string($userinfo->name);
    $email = $conn->real_escape_string($userinfo->email);
    $picture = $conn->real_escape_string($userinfo->picture);

    // Insert or update user
    $sql = "INSERT INTO users (google_id, name, email, picture)
            VALUES ('$google_id', '$name', '$email', '$picture')
            ON DUPLICATE KEY UPDATE name='$name', email='$email', picture='$picture'";

    $conn->query($sql);
    $conn->close();

    echo "Welcome, " . htmlspecialchars($userinfo->name);
    echo "<br><img src='" . htmlspecialchars($userinfo->picture) . "' />";
}

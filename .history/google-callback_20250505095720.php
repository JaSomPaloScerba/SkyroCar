<?php
require_once 'vendor/autoload.php';

// DB connection
$pdo = new PDO('mysql:host=localhost;dbname=your_database;charset=utf8', 'root', ''); // Adjust as needed

$client = new Google_Client();
$client->setClientId('992096689947-hroecv3o00n1hi3u6435hvdo4pag0378.apps.googleusercontent.com');
$client->setClientSecret('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');
$client->setRedirectUri('http://localhost/google-oauth-app/google-login.php');
$client->addScope('email');
$client->addScope('profile');

if (isset($_GET['code'])) {
    $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
    $client->setAccessToken($token);

    $oauth = new Google_Service_Oauth2($client);
    $googleUser = $oauth->userinfo->get();

    // Get user data
    $googleId = $googleUser->id;
    $name = $googleUser->name;
    $email = $googleUser->email;
    $picture = $googleUser->picture;

    // Insert into MySQL
    $stmt = $pdo->prepare("INSERT INTO users (google_id, name, email, picture)
                           VALUES (:google_id, :name, :email, :picture)
                           ON DUPLICATE KEY UPDATE name = :name, email = :email, picture = :picture");

    $stmt->execute([
        ':google_id' => $googleId,
        ':name'      => $name,
        ':email'     => $email,
        ':picture'   => $picture
    ]);

    echo "Welcome, $name! Your account has been saved.";
    echo "<br><img src='$picture' alt='Profile Picture'>";
} else {
    // Redirect to Google Login
    $authUrl = $client->createAuthUrl();
    header('Location: ' . filter_var($authUrl, FILTER_SANITIZE_URL));
}
?>+

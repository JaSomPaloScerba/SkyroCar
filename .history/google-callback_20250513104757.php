<?php
require_once __DIR__ . '/vendor/autoload.php';
session_start();

$client = new Google_Client();
$client->setClientId('
992096689947-hroecv3o00n1hi3u6435hvdo4pag0378.apps.googleusercontent.com');
$client->setClientSecret('YOUR_CLIENT_SECRET');
$client->setRedirectUri('http://localhost/google-callback.php');
$client->addScope('email');
$client->addScope('profile');

if (isset($_GET['code'])) {
    $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
    $client->setAccessToken($token);

    // Get user info
    $oauth = new Google_Service_Oauth2($client);
    $userInfo = $oauth->userinfo->get();

    // Store user info in session
    $_SESSION['user'] = [
        'id' => $userInfo->id,
        'name' => $userInfo->name,
        'email' => $userInfo->email,
        'picture' => $userInfo->picture,
    ];

    // ✅ Redirect to your app's main page
    header('Location: app-main.php');
    exit;
} else {
    echo 'Authorization code not found.';
}

<?php
require_once __DIR__ . '/vendor/autoload.php';
session_start();

// Create the Google Client FIRST
$client = new Google_Client();
$client->setClientId('your-client-id');
$client->setClientSecret('your-client-secret');
$client->setRedirectUri('http://localhost:8888/google-oauth-app/google-callback.php');
$client->addScope('email');
90
$client->addScope('profile');

// SSL SECURE BYPASS
$client->setHttpClient(new \GuzzleHttp\Client([
    'verify' => false
]));

if (isset($_GET['code'])) {
    $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
    $client->setAccessToken($token);

    $oauth = new Google_Service_Oauth2($client);
    $userInfo = $oauth->userinfo->get();

    $_SESSION['user'] = [
        'id' => $userInfo->id,
        'name' => $userInfo->name,
        'email' => $userInfo->email,
        'picture' => $userInfo->picture
    ];

    // Redirect to your custom page
    header('Location: app-main.php');
    exit;
} else {
    echo 'Authorization code not found';
}

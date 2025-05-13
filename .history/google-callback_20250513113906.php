
<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/vendor/autoload.php';
session_start();

$client = new Google_Client();
$client->setClientId('992096689947-hroecv3o00n1hi3u6435hvdo4pag0378.apps.googleusercontent.com');
$client->setClientSecret('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');
$client->setRedirectUri('http://localhost:8888/google-oauth-app/google-callback.php');
$client->addScope('email');
$client->addScope('profile');

// SSL certificate check
putenv('GOOGLE_API_USE_MTLS_ENDPOINT=always');
$client->setHttpClient(new \GuzzleHttp\Client([
    'verify' => false
]));

if (isset($_GET['code'])) {
    $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
    $client->setAccessToken($token);
    $oauth = new Google_Service_Oauth2($client);
    $userInfo = $oauth->userinfo->get();

    // session sAVE
    $_SESSION['user'] = [
        'id' => $userInfo->id,
        'name' => $userInfo->name,
        'email' => $userInfo->email,
        'picture' => $userInfo->picture,
    ];

    // app main redirect
    header('Location: app-main.php');
    exit;
} else {
    echo 'Authorization code not found.';
}

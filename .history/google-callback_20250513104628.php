<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once 'vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Connect to MySQL (MAMP defaults)
$pdo = new PDO("mysql:host=127.0.0.1;port=8889;dbname=oauth_db", "root", "root");

$client = new Google_Client();
$client->setClientId($_ENV['GOOGLE_CLIENT_ID']);
$client->setClientSecret($_ENV['GOOGLE_CLIENT_SECRET']);
$client->setRedirectUri($_ENV['GOOGLE_REDIRECT_URI']);

if (isset($_GET['code'])) {
    $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
    $client->setAccessToken($token);

    $google_service = new Google_Service_Oauth2($client);
    $user_info = $google_service->userinfo->get();

    $stmt = $pdo->prepare("INSERT INTO users (provider, provider_id, name, email) VALUES (?, ?, ?, ?)");
    $stmt->execute([
        'google',
        $user_info->id,
        $user_info->name,
        $user_info->email
    ]);

    echo "✅ Welcome, " . htmlspecialchars($user_info->name);
} else {
    echo "❌ Login failed.";
}

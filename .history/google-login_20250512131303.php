<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
?>


<?php
require_once 'vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$client = new Google_Client();
$client->setClientId($_ENV['992096689947-hroecv3o00n1hi3u6435hvdo4pag0378.apps.googleusercontent.com']);
$client->setClientSecret($_ENV['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']);
$client->setRedirectUri($_ENV['GOOGLE_REDIRECT_URI']);
$client->addScope("email");
$client->addScope("profile");

header('Location: ' . $client->createAuthUrl());

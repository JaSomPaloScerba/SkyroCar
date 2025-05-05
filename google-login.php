
<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once __DIR__ . '/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();


?>


<?php
require_once 'vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$client = new Google_Client();
$client->setClientId('992096689947-hroecv3o00n1hi3u6435hvdo4pag0378.apps.googleusercontent.com');
$client->setClientSecret('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');
$client->setRedirectUri('http://localhost/google-callback.php');
$client->addScope('email');
$client->addScope('profile');


header('Location: ' . $client->createAuthUrl());

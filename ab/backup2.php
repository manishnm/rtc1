<?php
require_once 'vendor/autoload.php';

session_start();

$client = new Google_Client();
$client->setAuthConfigFile('client_secrets.json');
$client->setRedirectUri('https://' . $_SERVER['HTTP_HOST'] . '/ab/backup2.php');
$client->addScope("https://www.googleapis.com/auth/drive.file");

if (! isset($_GET['code'])) {
  $auth_url = $client->createAuthUrl();
  header('Location: ' . filter_var($auth_url, FILTER_SANITIZE_URL));
} else {
  $client->authenticate($_GET['code']);
  $_SESSION['access_token1'] = $client->getAccessToken();
  $redirect_uri = 'https://' . $_SERVER['HTTP_HOST'] . '/backupsel.php';
  header('Location: ' . filter_var($redirect_uri, FILTER_SANITIZE_URL));
}
?>

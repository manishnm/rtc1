<?php
require_once 'vendor/autoload.php';

//ini_set('display_errors', '1'); 
	
session_start();

$client = new Google_Client();
$client->setAuthConfig('client_secrets.json');
$client->addScope("https://www.googleapis.com/auth/drive.file");

if (isset($_SESSION['access_token1']) && $_SESSION['access_token1']) {
  $client->setAccessToken($_SESSION['access_token1']);

  $fileMetadata = new Google_Service_Drive_DriveFile(array(
    'name' => 'Invoices',
    'mimeType' => 'application/vnd.google-apps.folder'));
    $driveService = new Google_Service_Drive($client);
$file = $driveService->files->create($fileMetadata, array(
    'fields' => 'id'));
    
    $fileMetadata = new Google_Service_Drive_DriveFile(array(
    'name' => 'ABC',
    'parents' => array($file->id),
    'mimeType' => 'application/vnd.google-apps.folder'));
    $driveService = new Google_Service_Drive($client);
$subfile = $driveService->files->create($fileMetadata, array(
    'fields' => 'id'));
    

} else {
  $redirect_uri = 'https://' . $_SERVER['HTTP_HOST'] . '/backup1.php';
  header('Location: ' . filter_var($redirect_uri, FILTER_SANITIZE_URL));
}



?>
<?php require_once "config.php";

	try {
		$accessToken = $helper->getAccessToken();
	} catch (\Facebook\Exceptions\FacebookResponseException $e) {
		echo "Response Exception: " . $e->getMessage();
		exit();
	} catch (\Facebook\Exceptions\FacebookSDKException $e) {
		echo "SDK Exception: " . $e->getMessage();
		exit();
	}

	if (!$accessToken) {
		header('Location: index.php');
		exit();
	}

	$oAuth2Client = $fb->getOAuth2Client();
	if (!$accessToken->isLongLived())
		$accessToken = $oAuth2Client->getLongLivedAccessToken($accessToken);

	$response = $fb->get("me?fields=name,albums{id,name,picture,count}", $accessToken);
	$userData = $response->getGraphNode()->asArray();
	 
	//$jsonData = file_get_contents($userData);
	//echo "<pre>";
	
	
	$_SESSION['userData'] = $userData;
//	echo "<a href='https://rtdownloader.000webhostapp.com/albums.php'>goto albums</a>";
	//var_dump($userData);
	$_SESSION['access_token'] = (string) $accessToken;
	header('Location: albums.php');
	//exit();
?>

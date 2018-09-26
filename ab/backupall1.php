<?php @session_start();
ini_set('max_execution_time', 1800);
require_once 'vendor/autoload.php';

 
	$token= $_SESSION['access_token'];
			$url= "https://graph.facebook.com/v3.1/me?fields=albums%7Bid%2Cname%2Cphotos%7Bimages%7D%7D&access_token=".$token;
			

function getData($url)
{
	//  Initiate curl
	$ch = curl_init();
	// Disable SSL verification
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	// Will return the response, if false it print the response
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	// Set the url
	curl_setopt($ch, CURLOPT_URL,$url);
	// Execute
	$result=json_decode(curl_exec($ch),true);
	// Closing
	curl_close($ch);      
	return $result;
}
   
$link=array();	
$links='';        	
function getNextParser($url)
{
	$innerData = getData($url);
	foreach($innerData['data'] as $image)
	     {
		 $GLOBALS['links'].=$image['images'][1]['source']." ";
	     }
	if(isset($innerData['paging']['next'])){
		getNextParser($innerData['paging']['next']);
	}
}
// Main calling 
$result = getData($url);     		
foreach($result['albums']['data'] as $album)
{
	$GLOBALS['links'].=$album['name']."||";
	foreach($album['photos']['data'] as $image)
	{
		 $GLOBALS['links'].= ($image['images'][1]['source'])." ";
	}
	if(isset($album['photos']['paging']['next']))
	{
		getNextParser($album['photos']['paging']['next']);
	}	
	$GLOBALS['links'].=" , ";
}	
$allAlbums = explode(',', $links);
array_pop($allAlbums);




         $tmp =$_SESSION['userData'];
        
 
$mainDirectory = "facebook_".$tmp['name']."_albums";
		$mainDirectory = str_replace(' ','',$mainDirectory);
		
		
$client = new Google_Client();
$client->setAuthConfig('client_secrets.json');
$client->addScope("https://www.googleapis.com/auth/drive.file");

if (isset($_SESSION['access_token1']) && $_SESSION['access_token1']) {

  $client->setAccessToken($_SESSION['access_token1']);
  $driveService = new Google_Service_Drive($client);
 $fileMetadata = new Google_Service_Drive_DriveFile(array(
    'name' => $mainDirectory,
    'mimeType' => 'application/vnd.google-apps.folder'));
    
$file = $driveService->files->create($fileMetadata, array(
    'fields' => 'id'));
     //loop
     	  foreach($allAlbums as $ab)
                {
                   $NameNLinks = explode('||', $ab);
                   
                
                   	$albumName = $NameNLinks[0];
				
				
					
						$albumName = str_replace(' ','',$albumName);
     
					    $fileMetadata = new Google_Service_Drive_DriveFile(array(
					    'name' => $albumName,
					    'parents' => array($file->id),
					    'mimeType' => 'application/vnd.google-apps.folder'));
					   $driveService = new Google_Service_Drive($client);
					   
					$subfile = $driveService->files->create($fileMetadata, array(
					    'fields' => 'id'));
					    
					 $picurls = explode(' ', $NameNLinks[1]);
                                         $count=1;
                                         
                   			 foreach($picurls as $url){
						if(!empty($url))
						{ 
						   $filedata = new Google_Service_Drive_DriveFile(array(
									'name' => $count.'jpg',
									'parents' => array($subfile->id)
								));
							$content = file_get_contents($url);
							$driveService = new Google_Service_Drive($client);
							$file1 = $driveService->files->create($filedata, array(
									'data' => $content,
									'mimeType' => 'image/jpeg',
									'uploadType' => 'multipart',
									'fields' => 'id')); 
						
						$count++;
						}
						//unset($subfile);
						}
					 
				}
					header('Location: uploadsuccess.php');
				
				
				}
					
					
?>

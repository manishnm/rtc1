	<?php 
	/**
	 * @package  Facebook Album downloader
	 * @category PHP
	 * @author   Mayurkumar Malaviya <mayurbmalaviya@gmail.com>
	 * @since    24-08-2018
	 * @link     https://mayurbmalaviya.000webhostapp.com/Facebook_App/index.php
	 *
	 * Here facebook login will fetch records by using token.
	 * using that token we have to access album data.
	 * then we can download single or multiple albums on server
	 * then we can download the album on pc as well as upload on google drive
	 * we can also see all the photos of respective albums click on that album.
	 **/
	@session_start();
	include("config.php");
	include("functions.php");

	if(isset($_SESSION['fb_access_token'])){
	  $token =  $_SESSION['fb_access_token'];
	}
	else
	{
		header("location:index.php");
		exit;
	}
	if(!isset($_SESSION['username'])){
		$_SESSION['username']='null';
	}
	if(!isset($_SESSION['images_of_all_albums']))
	{
		$_SESSION['images_of_all_albums']='null';
	}
	if(!isset($_SESSION['drivefilename']))
	{
		$_SESSION['drivefilename']='null';
	}
	if(isset($_SESSION['username'] ) ){
		$username = $_SESSION['username'];
	}


	// cluster of function
		$mainDirectory='';
		$filepath = '';
		$zipfilename = '';
		$arrayAlbum=array();
		$retval = $_SESSION['retrieveData'];
		$images_of_all_albums = $_SESSION['images_of_all_albums'];
		$total_album = count($images_of_all_albums);	
			
	/*if(isset($_SESSION['txtStoreAllImages']))
		{    
			
			$file = fopen($_SESSION['txtStoreAllImages'], "r") or exit("Unable to open file!");
			while(!feof($file))
			{
				$line = fgets($file). "<br>";
				$word_arr = explode(" ", $line);
				$count = 1;
				foreach($word_arr as $word){
					
					if($count==2)
							continue;
					echo $word."\n"; // required output
					$count++;
				}
			}
			fclose($file);
		}*/
	/* start selected album write into file */
	/*	if(isset($_REQUEST['btnDownload']))
		{
			if(isset($_REQUEST['images']))
			{
				foreach($_REQUEST['images'] as $sel)
				{
					foreach($images_of_all_albums as $key=>$val)
					{
						if(!strcmp(str_replace(' ','',$key),str_replace(' ','',$sel)))
						{
							foreach($val[0] as $images)
							{
								$my_file = 'file.txt';
								$handle = fopen($my_file, 'a') or die('Cannot open file:  '.$my_file);
								$data = $images['images'][0]['source']." ".$key."\n";
								fwrite($handle, $data);
								fclose($handle);
							}
						}
					}
				}
			}
			*/
			/* complete write into file of selected albums */
			
			
			$mainDirectory = "facebook_".$_SESSION['username']."_albums";
			$mainDirectory = str_replace(' ','',$mainDirectory);
			$path = $mainDirectory;
		//	$images_of_all_albums = $_SESSION['images_of_all_albums'];
			
			//echo $mainDirectory;
		if(isset($_REQUEST['btnDownload']))
		{
			if(isset($_REQUEST['images']))
			{
				
				//it will take individual selected albums
				foreach($_REQUEST['images'] as $sel)
				{	
					foreach($images_of_all_albums as $key=>$val)
					{
						$albumName = $key;
						$mainpath = $path;
						if(!strcmp(str_replace(' ','',$key),str_replace(' ','',$sel)))
						{
							$albumName = str_replace(' ','',$albumName);
							$albumPath = $mainpath."/".$albumName;
							
							// it will check the directory is available or not if not the create //
							if (!is_dir($albumPath)) {
								
								mkdir($albumPath,0777,true);
							}
							// code for download images //
							$imagePath = $albumPath."/";
							
							foreach($val[0] as $images)
							{
								file_put_contents($imagePath.$images['id'].'.jpg',file_get_contents($images['images'][0]['source']));
								writeOnFile($images['id'].'-'.$albumName);
							}
							
						}
						
					}
						
					
				}
				
				$zipfilename=createZipFile($mainDirectory);
			
				$_SESSION['zipfName'] = $zipfilename;
				deleteDir($path);
				$filepath = $_SERVER['DOCUMENT_ROOT']."/".$mainDirectory.".zip";

				$_SESSION['drivefilename'] = $path.".zip"; 	
				$_SESSION['download_file']='done';
			}
		
					
		}
		
		function sessionExpired()
		{
			unset($_SESSION['download_file']);
		}
		
	 
	?>
	<!DOCTYPE html>
	<html lang="en">
		<head>
			<meta charset="utf-8">
			<meta name="viewport" content="width=device-width, initial-scale=1">
			<title>Facebook Album Downloader</title>
			
			
			<link rel="shortcut icon" href="assets/images/favicon.png" />
			<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
			<!-- Bootstrap -->
			<link rel="stylesheet" type="text/css" href="assets/css/bootstrap.css">
			<!-- fancybox css File -->
			<link rel="stylesheet" type="text/css" href="assets/source/jquery.fancybox.css" media="screen" />
			<!-- Main css File -->
			<link rel="stylesheet" type="text/css" href="assets/css/style.css">
			<!-- Mobile Responsive CSS -->
			<link rel="stylesheet" type="text/css" href="assets/css/responsive.css">
			<script type="text/javascript">
			function selectAllcheckboxes(source)
					{
						if(document.getElementById('selectsAllchk').checked == true)
						{
							checkboxes = document.getElementsByName('images[]');
							for(var i=0, n=checkboxes.length;i<n;i++) {
								checkboxes[i].checked = source.checked;
							}
						}	
						else
						{
							checkboxes = document.getElementsByName('images[]');
							for(var i=0, n=checkboxes.length;i<n;i++) {
								checkboxes[i].checked = false;
							}
						}
					}
			</script>
		</head>
		<body>
		<form method="post">
		<!-- Header Logo & Menu Strat -->	
		<?php include("header.php"); ?>
		<!-- Header Logo & Menu End -->	


		<div class="container">
			<div class="row">
			<div class="box-content">
				<input type="checkbox" id="selectsAllchk" name="selectsAllchk" onClick='selectAllcheckboxes(this)'/>
				<span>Select all</span> &nbsp; &nbsp;
				<span><input type="submit" id="btnDownload" name="btnDownload" value="Download_Albums" /></span>
				
			</div>
			</div>
		</div>
		
		
		<div class="container downloadBTn">
			<div class="row">
			<?php 
				if(isset($_SESSION['download_file']))
				{
			?>
			<div class="col-md-6 col-sm-12">
				<div class="box-content1 text-center">
					<span><a href="<?php echo $_SESSION['zipfName'] ?>">Click here to download</a></span>
				</div> 
			</div>
			
			
			<div class="col-md-6 col-sm-12">
				<div class="box-content1 text-center">
					<span><a href="googleDriveUpload.php?filename=<?php echo $_SESSION['drivefilename']; ?>">Click here to upload on Google drive</a></span>
				</div>
			</div>
			</div>
			<?php  } ?>
		</div>
		
		<section id="galley-listWrap">
			<div class="section-padding2">
				<div class="container">
					<div class="row">
						<!--start coding-->
						
						<?php 
							for($album_index = 0;$album_index < $total_album;$album_index++)
							{
								$name = $retval['albums']['data'][$album_index]['name'];
								$total_images = $retval['albums']['data'][$album_index]['count'];
								if($total_images == 0)
								{
									continue;	
								}
							
						?>
							<div class="col-md-3 col-sm-3 col-xs-12">
								<div class="image-box">
									<a rel="" href="images_Data.php?albumname=<?php echo $name; ?>">
										<img src="<?php echo $retval['albums']['data'][$album_index]['photos']['data'][0]['images'][0]['source']; ?>" id='<?php echo $retval['albums']['data'][$album_index]['name']; ?>' alt="" style="width:200px;height:200px;"/>
									</a>
									<div class="box-content">
										<input type='checkbox' id="<?php echo $name; ?>" name='images[]' value='<?php echo  $name; ?>'/>
										<span><?php echo $name; ?></span>&nbsp;&nbsp;&nbsp;
									<!--	<span><i class="fa fa-download" style="font-size:18px;color:blue;" ></i></span>&nbsp;&nbsp;&nbsp;
										<span><img src="images/google_drive.png" height="30px" width="30px"></i></a></span>-->
		
									</div>
								</div>
							</div>
						
						<?php  } ?>

					</div>
				</div>
			</div>
		</section>

		<!-- Footer Section Strat -->	

		<section id="footer" class="footer-color">
			<div class="container">
				<div class="row">
					<div class="col-md-12">
						<div class="copyright">
							<p>2018 &copy; Mayur Malaviya. All Rights Reserved</p>
						</div>
					</div>
				</div>
			</div>
		</section>
		</form>
		<!-- Footer section end -->
		
			<script type="text/javascript" src="assets/js/jquery-2.1.3.min.js"></script>
			<!-- Bootstrap JS -->
			<script type="text/javascript" src="assets/js/bootstrap.min.js"></script>
			<script type="text/javascript" src="assets/source/jquery.fancybox.pack.js"></script>
			<script type="text/javascript" src="assets/source/jquery.mousewheel.pack.js"></script>
			<!-- Custom Script -->
			<script type="text/javascript" src="assets/js/scripts.js"></script>
		
		</body>
	</html>

<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
	    <title>Rtdownloader</title>
		
		<link rel="shortcut icon" href="images/favicon.png" />
		
		<!-- Bootstrap -->
		<link rel="stylesheet" type="text/css" href="css/bootstrap.css">
		<!-- fancybox css File -->
		<link rel="stylesheet" type="text/css" href="source/jquery.fancybox.css" media="screen" />
		<!-- Main css File -->
		<link rel="stylesheet" type="text/css" href="css/style.css">
		<!-- Mobile Responsive CSS -->
		<link rel="stylesheet" type="text/css" href="css/responsive.css">
		
	</head>
	<body>
	
	<!-- Header Logo & Menu Strat -->	
	
	<section id="header" class="header-color">
		<div class="container">
			<div class="row">
				<div class="col-md-12">
					<div class="logo text-center">
						<h2><a href="index.php">Rtdownloader</a></h2>
					</div>
				</div>
			</div>
		</div>
	</section>

	<!-- Header Logo & Menu End -->	
	
	<section id="galley-listWrap">
		<div class="section-padding">
			<div class="container">
				<div class="row">
				<div class="col-md-2 absolute"> 
				
					<input type="button" class="btn btn-info"  onclick="window.location = 'downloadall.php'"  value="Download All Albums">
						<br/>
						<br/>
					<input type="button" class="btn btn-info"  onclick="window.location = 'backupall.php'"  value="Backup All Albums">
      
				</div>
	<div class="col-md-8 text-left"> 
	  <?php
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




       
		
			
				$tmp = $_SESSION['userData'];
			echo "<h3><u> " . $tmp['name'] ."</u></h3>";
             $total = count( $tmp['albums'] );
			
			?>
	
			  
			   <h2>Select albums to upload Albums on your Google Drive </h2>
			 
			 
			 <form action="seluploader.php" method="post">
			 
			 
			 <?php
			 $i=0;
			   foreach($allAlbums as $ab)
                {
                   $NameNLinks = explode('||', $ab);
                   
                   
                
                   	$albumName = $NameNLinks[0];
                   	
                   	$id = $albumName;
					
				?>
				  <div class="col-md-4 col-sm-4 col-xs-12">
						<div class="img1">
				       <?php   echo "<b>" . $NameNLinks[0]."</b>";?>
				    <input type="checkbox" name="images[]" value="<?php echo $id;?>">    
				
				<a href="#<?php echo str_replace(" ","_",$albumName); ?>" class="fancybox">
				 <img src="<?php echo $tmp['albums'][$i]['picture']['url']; ?>" class="img img-responsive">
				</a>
				
				<?php
			
				 echo "Total Photos:".$tmp['albums'][$i]['count'];
				 echo "</div></div>";
				 $i++;
 
			 }
			 ?>
			 
			 <br/>
			 <br/>
			 <div class="row"><div class="col-md-12 text-center"> 
	          
	           
	           
	            <input type="submit" value="Upload Selected Albums" class="btn btn-primary"/>

	          
			 </div>	 
			 
	 
			 			 	 </div>	
		
			 			 	
    
	 
			 </form>
			 
					
					
					
				</div>
				<div class="col-md-2 sidenav">
                    	<input type="button" class="btn btn-default"  onclick="window.location = 'logout.php'"  value="Log Out">
               			 </div>
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
						<p>2018  Made by Manish Mangyani &copy; . (7405301432)</p>
					</div>
				</div>
			</div>
		</div>
	</section>
    

	<!-- Footer section end -->
	
		<script type="text/javascript" src="js/jquery-2.1.3.min.js"></script>
		<!-- Bootstrap JS -->
		<script type="text/javascript" src="js/bootstrap.min.js"></script>
		<script type="text/javascript" src="source/jquery.fancybox.pack.js"></script>
		<script type="text/javascript" src="source/jquery.mousewheel.pack.js"></script>
		<!-- Custom Script -->
			<script type="text/javascript" src="js/scripts.js"></script>
		<script type="text/javascript">
			$(document).ready(function() {			
				$('.fancybox').fancybox();
			});
		</script>	
		
	</body>
</html>
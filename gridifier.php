<!DOCTYPE html>
	<html>
		<head>
			<title>12 on 12 gridifier</title>
			<link rel="stylesheet" href="style.css" />
			<link rel="stylesheet" href="font/font.css" />
			<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script>
			<script src="gridifier.js"></script>
		</head>
		<body>
<?php
require_once 'config.php';
require_once 'Flickr/API.php';
require_once 'phpFlickr/phpFlickr.php';

//Authenticate flickr user
if(isset($_REQUEST['frob'])){
	//instantiate api
	$fApi = new phpFlickr(APIKEY, APISECRET);

	//get auth token
	$response = $fApi->auth_getToken($_REQUEST['frob']);

	$token = $response['token'];
	$userId = $response['user']['nsid'];

	$fApi->setToken($token);

	//get recent photos
	$photos = $fApi->photos_search(array('user_id'=>$userId,'per_page'=>12));
?>
		<span>Your Grid Preview:</span><br />
		<div id="grid">
		<table style="border-spacing: 4px">
<?php
	//start code string
	$codeString = "<table style=\"border-spacing: 4px\">";
	//Display recent photos
	$count = 0;
	foreach($photos['photo'] as $photo){
		if($count%3 == 0){
		?>
		<tr>
		<?php
		$codeString .= "<tr>";
		}
		$farmId = $photo['farm'];
		$serverId = $photo['server'];
		$photoId = $photo['id'];
		$photoSecret = $photo['secret'];
		$photoUrl = "http://farm{$farmId}.static.flickr.com/{$serverId}/{$photoId}_{$photoSecret}_m.jpg";
		$photoPageUrl = "http://www.flickr.com/photos/{$userId[0]}/{$photoId}";
		echo '<td><a href="'.$photoPageUrl.'" style="display: block; border: none;"><img height="150px" id="'.$photoId.'" src="'.$photoUrl.'" style="display: block; height: 150px; border: none;"/></a></td>'. "\n";
		$codeString .= '<td><a href="'.$photoPageUrl.'" style="display: block; border: none;"><img height="150px" id="'.$photoId.'" src="'.$photoUrl.'" style="display: block; height: 150px; border: none;"/></a></td>'. "\n";
		if($count%3 == 2){
			?>
		</tr>
			<?php
			$codeString .= "</tr>";
		}
		$count++;
	}
	?>
	</table>
	</div>
	<?php
	$codeString .= "</table>";
	?>
	<span>Copy Paste This Code Into Blogger!</span><br />
	<form>
		<textarea rows="20" id="code"><?php echo $codeString ?></textarea>
	</form>
	<?php

}
else{
	//create login url
	$apiSig = APISECRET."api_key".APIKEY."permsread";
	$apiSigHash = md5($apiSig);
	$loginUrl = "http://flickr.com/services/auth/?api_key=".APIKEY."&perms=read&api_sig=$apiSigHash";

	echo "<div id=\"title\"><span>12 On 12 Gridifier</span></div><br />";
	echo "<div id=\"login\"><span>Login With</span><br />";
	echo "<a href=\"$loginUrl\"><img height=\"100px\" src=\"Flickr-logo.png\" /></a></div>";
}
?>
</body>
</html>

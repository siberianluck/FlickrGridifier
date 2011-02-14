<!DOCTYPE html>
	<html>
		<head>
			<title>12 on 12 gridifier</title>
			<link rel="stylesheet" href="style.css" />
		</head>
		<body>
<?php
require_once 'config.php';

//Authenticate flickr user
if(isset($_REQUEST['frob'])){
	$apiMethod = 'flickr.auth.getToken';
	$apiSig = APISECRET."api_key".APIKEY."frob".$_REQUEST['frob']."method".$apiMethod;
	$apiSigHash = md5($apiSig);

	//get auth token
	$request = 'http://api.flickr.com/services/rest/?method='.$apiMethod.'&api_key='.APIKEY.'&frob='.$_REQUEST['frob'].'&api_sig='.$apiSigHash;
	$response = file_get_contents($request);
	//load xml
	$tokenXml = new SimpleXMLElement($response);
	$token = $tokenXml->auth->token;
	$userId = $tokenXml->auth->user['nsid'];

	//get recent photos
	$apiMethod = 'flickr.photos.search';
	$apiSig = APISECRET."api_key".APIKEY."auth_token".$token[0]."method".$apiMethod."per_page12"."user_id".$userId[0];
	$apiSigHash = md5($apiSig);
	$request = 'http://api.flickr.com/services/rest?method='.$apiMethod.'&api_key='.APIKEY.'&api_sig='.$apiSigHash.'&auth_token='.$token[0].'&user_id='.$userId[0].'&per_page=12';
	$photosXml = new SimpleXMLElement(file_get_contents($request));
?>
		<table style="border-spacing: 4px">
<?php
	//start code string
	$codeString = "<table style=\"border-spacing: 4px\">";
	//Display recent photos
	$count = 0;
	foreach($photosXml->photos->photo as $photo){
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
	<?php
	$codeString .= "</table>";
	?>
	<form>
		<textarea cols="120" rows="20"><?php echo $codeString ?></textarea>
	</form>
	<?php

}
else{
	//create login url
	$apiSig = APISECRET."api_key".APIKEY."permsread";
	$apiSigHash = md5($apiSig);
	$loginUrl = "http://flickr.com/services/auth/?api_key=".APIKEY."&perms=read&api_sig=$apiSigHash";

	echo "<h3>Login with:";
	echo "<a href=\"$loginUrl\"><img height=\"30px\" src=\"Flickr-logo.png\" /></a></h3>";
}

//Create blank grid

//Button to do output

//Send output code


?>
</body>
</html>

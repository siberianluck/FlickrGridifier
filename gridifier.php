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
	$apiSig = APISECRET."api_key".APIKEY."auth_token".$token[0]."method".$apiMethod."per_page20"."user_id".$userId[0];
	$apiSigHash = md5($apiSig);
	$request = 'http://api.flickr.com/services/rest?method='.$apiMethod.'&api_key='.APIKEY.'&api_sig='.$apiSigHash.'&auth_token='.$token[0].'&user_id='.$userId[0].'&per_page=20';
	$photosXml = new SimpleXMLElement(file_get_contents($request));

	//Display recent photos
	foreach($photosXml->photos->photo as $photo){
		$farmId = $photo['farm'];
		$serverId = $photo['server'];
		$photoId = $photo['id'];
		$photoSecret = $photo['secret'];
		$photoUrl = "http://farm{$farmId}.static.flickr.com/{$serverId}/{$photoId}_{$photoSecret}_s.jpg";
		echo '<img id="'.$photoId.'" src="'.$photoUrl.'" /> ';
	}

}
else{
	//create login url
	$apiSig = APISECRET."api_key".APIKEY."permsread";
	$apiSigHash = md5($apiSig);
	$loginUrl = "http://flickr.com/services/auth/?api_key=".APIKEY."&perms=read&api_sig=$apiSigHash";

	echo "<a href=\"$loginUrl\">Login!</a>";
}

//Create blank grid

//Button to do output

//Send output code


?>
</body>
</html>

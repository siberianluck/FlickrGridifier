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
		<table>
<?php
	//Display recent photos
	$count = 0;
	foreach($photosXml->photos->photo as $photo){
		if($count%3 == 0){
		?>
		<tr>
		<?php
		}
		$farmId = $photo['farm'];
		$serverId = $photo['server'];
		$photoId = $photo['id'];
		$photoSecret = $photo['secret'];
		$photoUrl = "http://farm{$farmId}.static.flickr.com/{$serverId}/{$photoId}_{$photoSecret}_m.jpg";
		$photoPageUrl = "http://www.flickr.com/photos/{$userId[0]}/{$photoId}";
		echo '<td class="imgcell"><a href="'.$photoPageUrl.'"><img height="150px" id="'.$photoId.'" src="'.$photoUrl.'" /></a></td>'. "\n";
		if($count%3 == 2){
			?>
		</tr>
			<?php
		}
		$count++;
	}
	?>
	</table>
	<?php

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

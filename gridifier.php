<?php
require_once config.php;

//Authenticate flickr user
//create login url
$apiSig = APISECRET."api_key".APIKEY."permsread";
$apiSigHash = md5($apiSig);
$loginUrl = "http://flickr.com/services/auth/?api_key=".APIKEY."&perms=read&api_sig=$apiSigHash";

echo "<a href=\"$loginUrl\">Login!</a>";

//Get recent flickr pictures
//flickr.photos.search needs user_id and per_page=20

//Create blank grid

//Button to do output

//Send output code

?>

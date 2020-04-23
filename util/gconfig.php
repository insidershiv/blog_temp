<?php

//Include Google Client Library for PHP autoload file
require_once '../vendor/autoload.php';

//Make object of Google API Client for call Google API
$google_client = new Google_Client();

//Set the OAuth 2.0 Client ID
$google_client->setClientId('1089665626015-rs4nfq9l5cg46rd3dn0he90gvap6heau.apps.googleusercontent.com');

//Set the OAuth 2.0 Client Secret key
$google_client->setClientSecret('IBJnBd0l__R1bllFstkiVmGT');

//Set the OAuth 2.0 Redirect URI
$google_client->setRedirectUri('http://localhost:8080/blog/view/login.php');

//
$google_client->addScope('email');

$google_client->addScope('profile');

//start session on web page


?>
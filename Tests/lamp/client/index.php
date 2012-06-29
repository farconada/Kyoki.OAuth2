<?php
require('./lib/Client.php');
require('./lib/GrantType/IGrantType.php');
require('./lib/GrantType/AuthorizationCode.php');

const CLIENT_ID     = 'AAAAAAAAAAAAAA';
const CLIENT_SECRET = 'SSSSSSSSSSSSSSS';
const REDIRECT_URI           = 'http://localhost:8080/client/';
const AUTHORIZATION_ENDPOINT = 'http://localhost:8080/authorize';
const TOKEN_ENDPOINT         = 'http://localhost:8080/token';
const RESOURCE_ENDPOINT	     = 'http://localhost:8080/resource';

$client = new OAuth2\Client(CLIENT_ID, CLIENT_SECRET);
if (!isset($_GET['code']))
{
    $auth_url = $client->getAuthenticationUrl(AUTHORIZATION_ENDPOINT, REDIRECT_URI,array('scope' => 'myscope'));
    header('Location: ' . $auth_url);
    die('Redirect');
}
else
{
    $params = array('code' => $_GET['code'], 'redirect_uri' => REDIRECT_URI);
    echo 'code: ' . $params['code'] . " <br />\n";
    $response = $client->getAccessToken(TOKEN_ENDPOINT, 'authorization_code', $params);
    $info = $response['result'];
    echo 'access_token: ' . $info['access_token'] . " <br />\n";
    $client->setAccessToken($info['access_token']);
    $client->setAccessTokenType(OAuth2\Client::ACCESS_TOKEN_OAUTH);
    $response = $client->fetch(RESOURCE_ENDPOINT);
    var_dump($response);
    var_dump($client);
}

<?php
/*                                                                        *
 * This script belongs to the Kyoki.OAuth2 package.                        *
 * @author Fernando Arconada <fernando.arconada@gmail.com>                *
 *
 * It is free software; you can redistribute it and/or modify it under    *
 * the terms of the GNU Lesser General Public License, either version 3   *
 * of the License, or (at your option) any later version.                 *
 *                                                                        *
 *                                                                        */
require('./lib/Client.php');
require('./lib/GrantType/IGrantType.php');
require('./lib/GrantType/AuthorizationCode.php');

const CLIENT_ID     = '5c4aa9343a3f01d3ed7ff337da1b8e1924f4038d';
const CLIENT_SECRET = 'abab2a4d37723eb4586810a82e189af81bb0ed62';

const REDIRECT_URI           = 'http://flow3.localhost/client/';
const AUTHORIZATION_ENDPOINT = 'http://flow3.localhost/authorize';
const TOKEN_ENDPOINT         = 'http://flow3.localhost/token';

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
    $response = $client->fetch('http://flow3.localhost/resource');
    var_dump($response, $response['result']);
}

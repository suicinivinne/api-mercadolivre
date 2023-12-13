<?php
header("Access-Control-Allow-Origin: *");
ini_set("allow_url_fopen", 1);

require __DIR__ . '/src/AccessToken.php';

$accessToken = new AccessToken\AccessToken();
use AccessToken\AccessToken;

$code 			= ''; // O CÓDIGO DE AUTORIZAÇÃO OBTIDO NO VALOR DO PARÂMETRO DO REDIRECIONAMENTO DA URL.
$refresh_token 	= ''; // O REFRESH TOKEN DO PASSO DE APROVAÇÃO GUARDADO PREVIAMENTE.
$client_id 		= ''; // É O APP ID DO APLICATIVO QUE FOI CRIADO.
$client_secret 	= ''; // É A SECRET KEY QUE FOI GERADO AO CRIAR O APLICATIVO.
$redirect_uri 	= ''; // O REDIRECT URI CONFIGURADO PARA SEU APLICATIVO NÃO PODE CONTER INFORMAÇÕES VARIÁVEIS.

$accessToken->setConfig(
	$code,
	$refresh_token,
	$client_id,
	$client_secret,
	$redirect_uri,
);

if(!$accessToken->getError()){
	$result['access_token'] 	= $accessToken->new_access_token;
	$result['refresh_token'] 	= $accessToken->new_refresh_token;
}else{
	$result['error'] = $accessToken->getError();
}
var_dump($result);
?>
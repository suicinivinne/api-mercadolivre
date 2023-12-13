<?php

namespace AccessToken;

class AccessToken
{
	private $code;
	private $refresh_token;
	private $client_id;
	private $client_secret;
	private $redirect_uri;
	private $error;

    public function setConfig(
	/*
		$code 			- TO GENERATE FIRST ACCESS TOKEN
		$refresh_token 	- TO GENERATE RENEW ACCESS TOKEN BEFORE IT EXPIRES
	*/
	$code = null,
	$refresh_token = null,
	$client_id,
	$client_secret,
	$redirect_uri
    )
    {    	
    	if (!empty($code) && !empty($refresh_token)){
		    $this->error = 'ENTER ONLY ONE {CODE} TO GENERATE FIRST ACCESS TOKEN OR {REFRESH_TOKEN} TO GENERATE RENEW ACCESS TOKEN BEFORE IT EXPIRES';
    		return false;
    	} elseif (empty($code) && empty($refresh_token)){
		    $this->error = 'ENTER {CODE} TO GENERATE FIRST ACCESS TOKEN OR {REFRESH_TOKEN} TO GENERATE RENEW ACCESS TOKEN BEFORE IT EXPIRES';
    		return false;
    	} else {

		  	if (!empty($code)){
		  		$parametros['grant_type'] 		= 'authorization_code';
		  		$parametros['type'] 			= 'code';
		  		$parametros['token'] 			= $code;
		  	}
		  	if (!empty($refresh_token)){
		  		$parametros['grant_type'] 		= 'refresh_token';
		  		$parametros['type'] 			= 'refresh_token';
		  		$parametros['token'] 			= $refresh_token;
		  	}
		  	$parametros['client_id'] 		= $client_id;
		  	$parametros['client_secret'] 	= $client_secret;
		  	$parametros['redirect_uri'] 	= $redirect_uri;
	        $this->setAccessToken($parametros);
	        return true;
    	}
    }//setConfig

    private function setAccessToken($parametros)
    {
	    $curl = curl_init();
	    curl_setopt_array($curl, array(
	      CURLOPT_URL => 'https://api.mercadolibre.com/oauth/token?grant_type='.$parametros['grant_type'].'&client_id='.$parametros['client_id'].'&client_secret='.$parametros['client_secret'].'&'.$parametros['type'].'='.$parametros['token'].'&redirect_uri='.$parametros['redirect_uri'],
	      CURLOPT_SSL_VERIFYHOST 	=> false,
	      CURLOPT_SSL_VERIFYPEER 	=> false,
	      CURLOPT_RETURNTRANSFER 	=> true,
	      CURLOPT_ENCODING 			=> '',
	      CURLOPT_MAXREDIRS 		=> 10,
	      CURLOPT_TIMEOUT 			=> 0,
	      CURLOPT_FOLLOWLOCATION 	=> true,
	      CURLOPT_HTTP_VERSION 		=> CURL_HTTP_VERSION_1_1,
	      CURLOPT_CUSTOMREQUEST 	=> 'POST',
	      CURLOPT_HTTPHEADER 		=> array(
	        'Origin: https://api.mercadolibre.com',
	        'Content-Type: application/json'
	      ),
	    ));
	    $response	= curl_exec($curl);
	    $res        = json_decode($response);
	    if (isset($res->error)) {
	    	$this->error = mb_strtoupper($res->error_description);
    		return false;	    	
	    } else {
        	$this->new_access_token 	= $res->access_token;
        	$this->new_refresh_token 	= $res->refresh_token;

	    }
    }//setAccessToken

    public function getError()
    {
        return $this->error;
    }//getError
}//AccessToken
?>
<?php
require_once(SFSI_PLUS_DOCROOT.'/helpers/OAuth.php');

class Plus_LinkedIn {
	public $base_url = "http://api.linkedin.com";
	public $secure_base_url = "https://api.linkedin.com";
	public $oauth_callback = "oob";
	public $consumer;
	public $request_token;
	public $access_token;
	public $oauth_verifier;
	public $signature_method;
	public $request_token_path;
	public $access_token_path;
	public $authorize_path;

	function __construct($consumer_key, $consumer_secret,$request_token, $oauth_callback = NULL)
	{

		if($oauth_callback) {
			$this->oauth_callback = $oauth_callback;
		}
                $this->request_token=    
		$this->consumer = new Plus_OAuthConsumer($consumer_key, $consumer_secret, $this->oauth_callback);
		$this->signature_method = new Plus_OAuthSignatureMethod_HMAC_SHA1();
		$this->request_token_path = $this->secure_base_url . "/uas/oauth/requestToken?scope=r_basicprofile+r_emailaddress+r_network+w_messages";
		$this->access_token_path = $this->secure_base_url . "/uas/oauth/accessToken";
		$this->authorize_path = $this->secure_base_url . "/uas/oauth/authorize";
	}

	function getRequestToken()
	{
		$consumer = $this->consumer;
		$request = Plus_OAuthRequest::from_consumer_and_token($consumer, NULL, "GET", $this->request_token_path);
		$request->set_parameter("oauth_callback", $this->oauth_callback);
		$request->sign_request($this->signature_method, $consumer, NULL);
		$headers = Array();
		$url = $request->to_url();
		$response = $this->httpRequest($url, $headers, "GET");
		parse_str($response, $response_params);
		$this->request_token = new Plus_OAuthConsumer($response_params['oauth_token'], $response_params['oauth_token_secret'], 1);
	}

	function generateAuthorizeUrl()
	{
		$consumer = $this->consumer;
		$request_token = $this->request_token;
		return $this->authorize_path . "?oauth_token=" . $request_token->key;
	}

	function getAccessToken($oauth_verifier)
	{
		$request = Plus_OAuthRequest::from_consumer_and_token($this->consumer, $this->request_token, "GET", $this->access_token_path);
		$request->set_parameter("oauth_verifier", $oauth_verifier);
		$request->sign_request($this->signature_method, $this->consumer, $this->request_token);
		$headers = Array();
		$url = $request->to_url();
		$response = $this->httpRequest($url, $headers, "GET");
		parse_str($response, $response_params);
		$this->access_token = new Plus_OAuthConsumer($response_params['oauth_token'], $response_params['oauth_token_secret'], 1);
	}

        function getCompanyFollowersById($companyID = "")
	{
		$api_url = $this->base_url . "/v1/companies/".$companyID.":(num-followers)/";
		$request = Plus_OAuthRequest::from_consumer_and_token($this->consumer, $this->access_token, "GET", $api_url);
		$request->sign_request($this->signature_method, $this->consumer, $this->access_token);
		$auth_header = $request->to_header("https://api.linkedin.com"); # this is the realm
		$response = $this->httpRequest($api_url, $auth_header, "GET");
		return $response;
	}
        function  getCompanyFollowersByName($companyName = "")
	{
		$api_url = $this->base_url . "/v1/companies/universal-name=".$companyName.":(num-followers)/";
		$request = Plus_OAuthRequest::from_consumer_and_token($this->consumer, $this->access_token, "GET", $api_url);
		$request->sign_request($this->signature_method, $this->consumer, $this->access_token);
		$auth_header = $request->to_header("https://api.linkedin.com"); # this is the realm
		$response = $this->httpRequest($api_url, $auth_header, "GET");
		
                return $response;
	}

	

	function httpRequest($url, $auth_header, $method, $body = NULL)
	{
		if (!$method) {
			$method = "GET";
		};

		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_HEADER, 0);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_HTTPHEADER, array($auth_header)); // Set the headers.

		if ($body) {
			curl_setopt($curl, CURLOPT_POST, 1);
			curl_setopt($curl, CURLOPT_POSTFIELDS, $body);
			curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
			curl_setopt($curl, CURLOPT_HTTPHEADER, array($auth_header, "Content-Type: text/xml;charset=utf-8"));
		}

		$data = curl_exec($curl);
		curl_close($curl);
		return $data;
	}

}

function Plus_array_msort($array, $cols)
{
    $colarr = array();
    foreach ($cols as $col => $order) {
        $colarr[$col] = array();
        foreach ($array as $k => $row) { $colarr[$col]['_'.$k] = strtolower($row[$col]); }
    }
    $eval = 'array_multisort(';
    foreach ($cols as $col => $order) {
        $eval .= '$colarr[\''.$col.'\'],'.$order.',';
    }
    $eval = substr($eval,0,-1).');';
    eval($eval);
    $ret = array();
    foreach ($colarr as $col => $arr) {
        foreach ($arr as $k => $v) {
            $k = substr($k,1);
            if (!isset($ret[$k])) $ret[$k] = $array[$k];
            $ret[$k][$col] = $array[$k][$col];
        }
    }
    return $ret;

}

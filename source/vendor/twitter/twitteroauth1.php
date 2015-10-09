<?php

//require_once('OAuth.php');

/**
 * Twitter OAuth class
 */
class TwitterOAuth {
  /* Contains the last HTTP status code returned. */
  public static $http_code;
  /* Contains the last API call. */
  public static  $url;
  /* Set up the API root URL. */
  public static  $host = "https://api.twitter.com/1.1/";
  /* Set timeout default. */
  public static  $timeout = 30;
  /* Set connect timeout. */
  public static  $connecttimeout = 30; 
  /* Verify SSL Cert. */
  public static  $ssl_verifypeer = FALSE;
  /* Respons format. */
  public static  $format = 'json';
  /* Decode returned json data. */
  public static  $decode_json = TRUE;
  /* Contains the last HTTP headers returned. */
  public static  $http_info;
  /* Set the useragnet. */
  public static  $useragent = 'TwitterOAuth v0.2.0-beta2';
  
  public static  $sha1_method;
   
  public static  $consumer;
  
  public static $token;
  
  public static  $consumer_key;
  public static  $consumer_secret;
  public static  $oauth_token;
  public static  $oauth_token_secret;
  public static  $http_header;

  /* Immediately retry the API call if the response was not successful. */
  //public $retry = TRUE;




  /**
   * Set API URLS
   */
   static function accessTokenURL()  { return 'https://api.twitter.com/oauth/access_token'; }
   static function authenticateURL() { return 'https://api.twitter.com/oauth/authenticate'; }
   static function authorizeURL()    { return 'https://api.twitter.com/oauth/authorize'; }
   static function requestTokenURL() { return 'https://api.twitter.com/oauth/request_token'; }

  /**
   * Debug helpers
   */
   static function lastStatusCode() { return $this->http_status; }
   static function lastAPICall() { return $this->last_api_call; }

  /**
   * construct TwitterOAuth object
   */
  static function setAuth($consumer_key, $consumer_secret, $oauth_token = NULL, $oauth_token_secret = NULL) {
	  self::$consumer_key=$consumer_key;
	  self::$consumer_secret=$consumer_secret;
	  self::$oauth_token=$oauth_token;
	  self::$oauth_token_secret=$oauth_token_secret;
    self::$sha1_method = new OAuthSignatureMethod_HMAC_SHA1();
    self::$consumer = new OAuthConsumer($consumer_key, $consumer_secret);
    if (!empty($oauth_token) && !empty($oauth_token_secret)) {
      self::$token = new OAuthConsumer($oauth_token, $oauth_token_secret);
    } else {
      self::$token = NULL;
    }
  }


  /**
   * Get a request_token from Twitter
   *
   * @returns a key/value array containing oauth_token and oauth_token_secret
   */
   static function getRequestToken($oauth_callback) {
    $parameters = array();
    $parameters['oauth_callback'] = $oauth_callback; 
    $request = self::oAuthRequest(self::requestTokenURL(), 'GET', $parameters);	
    $token = OAuthUtil::parse_parameters($request);
    self::$token = new OAuthConsumer($token['oauth_token'], $token['oauth_token_secret']);
    return $token;
  }

  /**
   * Get the authorize URL
   *
   * @returns a string
   */
   static function getAuthorizeURL($token, $sign_in_with_twitter = FALSE) {
    if (is_array($token)) {
      $token = $token['oauth_token'];
    }
    if (empty($sign_in_with_twitter)) {
      return self::authorizeURL() . "?oauth_token={$token}";
    } else {
       return self::authenticateURL() . "?oauth_token={$token}";
    }
  }

  /**
   * Exchange request token and secret for an access token and
   * secret, to sign API calls.
   *
   * @returns array("oauth_token" => "the-access-token",
   *                "oauth_token_secret" => "the-access-secret",
   *                "user_id" => "9436992",
   *                "screen_name" => "abraham")
   */
   static function getAccessToken($oauth_verifier) {
    $parameters = array();
    $parameters['oauth_verifier'] = $oauth_verifier;
    $request = self::oAuthRequest(self::accessTokenURL(), 'GET', $parameters);
    $token = OAuthUtil::parse_parameters($request);
	if(!empty($token['oauth_token'])){
    	self::$token = new OAuthConsumer($token['oauth_token'], $token['oauth_token_secret']);
	}
    return $token;
  }

  /**
   * One time exchange of username and password for access token and secret.
   *
   * @returns array("oauth_token" => "the-access-token",
   *                "oauth_token_secret" => "the-access-secret",
   *                "user_id" => "9436992",
   *                "screen_name" => "abraham",
   *                "x_auth_expires" => "0")
   */  
   static function getXAuthToken($username, $password) {
    $parameters = array();
    $parameters['x_auth_username'] = $username;
    $parameters['x_auth_password'] = $password;
    $parameters['x_auth_mode'] = 'client_auth';
    $request = self::oAuthRequest(self::accessTokenURL(), 'POST', $parameters);
    $token = OAuthUtil::parse_parameters($request);
    self::$token = new OAuthConsumer($token['oauth_token'], $token['oauth_token_secret']);
    return $token;
  }

  /**
   * GET wrapper for oAuthRequest.
   */
   public static function get($url, $parameters = array()) {
	   echo $url;	 
    $response = self::oAuthRequest($url, 'GET', $parameters);
	print_r($response);exit;
    if (self::$format === 'json' && self::$decode_json) {
      return json_decode($response);
    }
    return $response;
  }
  
  /**
   * POST wrapper for oAuthRequest.
   */
  static  function post($url, $parameters = array()) {
    $response = self::oAuthRequest($url, 'POST', $parameters);
    if ($this->format === 'json' && self::$decode_json) {
      return json_decode($response);
    }
    return $response;
  }

  /**
   * DELETE wrapper for oAuthReqeust.
   */
   static function delete($url, $parameters = array()) {
    $response = self::oAuthRequest($url, 'DELETE', $parameters);
    if ($this->format === 'json' && self::$decode_json) {
      return json_decode($response);
    }
    return $response;
  }

  /**
   * Format and sign an OAuth / API request
   */
   static function oAuthRequest($url, $method, $parameters) {
    if (strrpos($url, 'https://') !== 0 && strrpos($url, 'http://') !== 0) {
      $url = "{".self::$host."}{$url}.{".self::$format."}";
    }
    $request = \OAuthRequest::from_consumer_and_token(self::$consumer, self::$token, $method, $url, $parameters);
    $request->sign_request(self::$sha1_method, self::$consumer, self::$token);
	echo $request->to_url();exit;
    switch ($method) {
    case 'GET':
      return self::http($request->to_url(), 'GET');
    default:
      return self::http($request->get_normalized_http_url(), $method, $request->to_postdata());
    }
  }

  /**
   * Make an HTTP request
   *
   * @return API results
   */
   static function http($url, $method, $postfields = NULL) {
    self::$http_info = array();
    $ci = curl_init();
    /* Curl settings */
    curl_setopt($ci, CURLOPT_USERAGENT, self::$useragent);
    curl_setopt($ci, CURLOPT_CONNECTTIMEOUT, self::$connecttimeout);
    curl_setopt($ci, CURLOPT_TIMEOUT, self::$timeout);
    curl_setopt($ci, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($ci, CURLOPT_HTTPHEADER, array('Expect:'));
    curl_setopt($ci, CURLOPT_SSL_VERIFYPEER, self::$ssl_verifypeer);
    curl_setopt($ci, CURLOPT_HEADERFUNCTION, array(new TwitterOAuth(self::$consumer_key,self::$consumer_secret), 'getHeader'));
    curl_setopt($ci, CURLOPT_HEADER, FALSE);

    switch ($method) {
      case 'POST':
        curl_setopt($ci, CURLOPT_POST, TRUE);
        if (!empty($postfields)) {
          curl_setopt($ci, CURLOPT_POSTFIELDS, $postfields);
        }
        break;
      case 'DELETE':
        curl_setopt($ci, CURLOPT_CUSTOMREQUEST, 'DELETE');
        if (!empty($postfields)) {
          $url = "{$url}?{$postfields}";
        }
    }

    curl_setopt($ci, CURLOPT_URL, $url);
    $response = curl_exec($ci);
    self::$http_code = curl_getinfo($ci, CURLINFO_HTTP_CODE);
    self::$http_info = array_merge(self::$http_info, curl_getinfo($ci));
    self::$url = $url;
    curl_close ($ci);
    return $response;
  }

  /**
   * Get the header info to store.
   */
   static function getHeader($ch, $header) {
    $i = strpos($header, ':');
    if (!empty($i)) {
      $key = str_replace('-', '_', strtolower(substr($header, 0, $i)));
      $value = trim(substr($header, $i + 2));
      self::$http_header[$key] = $value;
    }
    return strlen($header);
  }
}

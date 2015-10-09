<?php
/**
 * Copyright 2011 Facebook, Inc.
 *
 * Licensed under the Apache License, Version 2.0 (the "License"); you may
 * not use this file except in compliance with the License. You may obtain
 * a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS, WITHOUT
 * WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the
 * License for the specific language governing permissions and limitations
 * under the License.
 */

/*if (!function_exists('curl_init')) {
  throw new Exception('Facebook needs the CURL PHP extension.');
}
if (!function_exists('json_decode')) {
  throw new Exception('Facebook needs the JSON PHP extension.');
}*/

/**
 * Thrown when an API call returns an exception.
 *
 * @author Naitik Shah <naitik@facebook.com>
 */
class FacebookApiException extends Exception
{
  /**
   * The result from the API server that represents the exception information.
   *
   * @var mixed
   */
  protected $result;

  /**
   * Make a new API Exception with the given result.
   *
   * @param array $result The result from the API server
   */
  public function __construct($result) {
    $this->result = $result;

    $code = 0;
    if (isset($result['error_code']) && is_int($result['error_code'])) {
      $code = $result['error_code'];
    }

    if (isset($result['error_description'])) {
      // OAuth 2.0 Draft 10 style
      $msg = $result['error_description'];
    } else if (isset($result['error']) && is_array($result['error'])) {
      // OAuth 2.0 Draft 00 style
      $msg = $result['error']['message'];
    } else if (isset($result['error_msg'])) {
      // Rest server style
      $msg = $result['error_msg'];
    } else {
      $msg = 'Unknown Error. Check getResult()';
    }

    parent::__construct($msg, $code);
  }

  /**
   * Return the associated result object returned by the API server.
   *
   * @return array The result from the API server
   */
  public function getResult() {
    return $this->result;
  }

  /**
   * Returns the associated type for the error. This will default to
   * 'Exception' when a type is not available.
   *
   * @return string
   */
  public function getType() {
    if (isset($this->result['error'])) {
      $error = $this->result['error'];
      if (is_string($error)) {
        // OAuth 2.0 Draft 10 style
        return $error;
      } else if (is_array($error)) {
        // OAuth 2.0 Draft 00 style
        if (isset($error['type'])) {
          return $error['type'];
        }
      }
    }

    return 'Exception';
  }

  /**
   * To make debugging easier.
   *
   * @return string The string representation of the error
   */
  public function __toString() {
    $str = $this->getType() . ': ';
    if ($this->code != 0) {
      $str .= $this->code . ': ';
    }
    return $str . $this->message;
  }
}

/**
 * Provides access to the Facebook Platform.  This class provides
 * a majority of the functionality needed, but the class is abstract
 * because it is designed to be sub-classed.  The subclass must
 * implement the four abstract methods listed at the bottom of
 * the file.
 *
 * @author Naitik Shah <naitik@facebook.com>
 */
class BaseFacebook
{
  /**
   * Version.
   */
  const VERSION = '3.2.3';

  /**
   * Signed Request Algorithm.
   */
  const SIGNED_REQUEST_ALGORITHM = 'HMAC-SHA256';

  /**
   * Default options for curl.
   *
   * @var array
   */
  public static $CURL_OPTS = array(
    CURLOPT_CONNECTTIMEOUT => 10,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_TIMEOUT        => 60,
    CURLOPT_USERAGENT      => 'facebook-php-3.2',
  );

  /**
   * List of query parameters that get automatically dropped when rebuilding
   * the current URL.
   *
   * @var array
   */
  protected static $DROP_QUERY_PARAMS = array(
    'code',
    'state',
    'signed_request',
  );

  /**
   * Maps aliases to Facebook domains.
   *
   * @var array
   */
  public static $DOMAIN_MAP = array(
    'api'         => 'https://api.facebook.com/',
    'api_video'   => 'https://api-video.facebook.com/',
    'api_read'    => 'https://api-read.facebook.com/',
    'graph'       => 'https://graph.facebook.com/',
    'graph_video' => 'https://graph-video.facebook.com/',
    'www'         => 'https://www.facebook.com/',
  );

  /**
   * The Application ID.
   *
   * @var string
   */
  protected static $appId;

  /**
   * The Application App Secret.
   *
   * @var string
   */
  protected static  $appSecret;

  /**
   * The ID of the Facebook user, or 0 if the user is logged out.
   *
   * @var integer
   */
  protected static  $user;

  /**
   * The data from the signed_request token.
   *
   * @var string
   */
  protected static  $signedRequest;

  /**
   * A CSRF state variable to assist in the defense against CSRF attacks.
   *
   * @var string
   */
  protected static  $state;

  /**
   * The OAuth access token received in exchange for a valid authorization
   * code.  null means the access token has yet to be determined.
   *
   * @var string
   */
  protected static  $accessToken = null;

  /**
   * Indicates if the CURL based @ syntax for file uploads is enabled.
   *
   * @var boolean
   */
  protected static  $fileUploadSupport = false;

  /**
   * Indicates if we trust HTTP_X_FORWARDED_* headers.
   *
   * @var boolean
   */
  protected static  $trustForwarded = false;

  /**
   * Indicates if signed_request is allowed in query parameters.
   *
   * @var boolean
   */
  protected static  $allowSignedRequest = true;
  
  protected static $sharedSessionID;
  
  const FBSS_COOKIE_NAME = 'fbss';

  /**
   * We can set this to a high number because the main session
   * expiration will trump this.
   */
  const FBSS_COOKIE_EXPIRE = 31556926; // 1 year

  /**
   * Initialize a Facebook Application.
   *
   * The configuration:
   * - appId: the application ID
   * - secret: the application secret
   * - fileUpload: (optional) boolean indicating if file uploads are enabled
   * - allowSignedRequest: (optional) boolean indicating if signed_request is
   *                       allowed in query parameters or POST body.  Should be
   *                       false for non-canvas apps.  Defaults to true.
   *
   * @param array $config The application configuration
   */
  public static function setAuth($config) {
    self::setAppId($config['appId']);
    self::setAppSecret($config['secret']);
    if (isset($config['fileUpload'])) {
      self::setFileUploadSupport($config['fileUpload']);
    }
    if (isset($config['trustForwarded']) && $config['trustForwarded']) {
      self::$trustForwarded = true;
    }
    if (isset($config['allowSignedRequest'])
        && !$config['allowSignedRequest']) {
        self::$allowSignedRequest = false;
    }
    $state = self::getPersistentData('state');
    if (!empty($state)) {
      self::$state = $state;
    }
  }

  /**
   * Set the Application ID.
   *
   * @param string $appId The Application ID
   *
   * @return BaseFacebook
   */
  public  static function setAppId($appId) {
    self::$appId = $appId;
    //return $this;
  }

  /**
   * Get the Application ID.
   *
   * @return string the Application ID
   */
  public  static function getAppId() {
    return self::$appId;
  }

  /**
   * Set the App Secret.
   *
   * @param string $apiSecret The App Secret
   *
   * @return BaseFacebook
   * @deprecated Use setAppSecret instead.
   * @see setAppSecret()
   */
  public  static  function setApiSecret($apiSecret) {
    self::$setAppSecret($apiSecret);
   // return $this;
  }

  /**
   * Set the App Secret.
   *
   * @param string $appSecret The App Secret
   *
   * @return BaseFacebook
   */
  public  static function setAppSecret($appSecret) {
    self::$appSecret = $appSecret;
    //return $this;
  }

  /**
   * Get the App Secret.
   *
   * @return string the App Secret
   *
   * @deprecated Use getAppSecret instead.
   * @see getAppSecret()
   */
  public static  function getApiSecret() {
    return self::$getAppSecret();
  }

  /**
   * Get the App Secret.
   *
   * @return string the App Secret
   */
  public static  function getAppSecret() {
    return self::$appSecret;
  }

  /**
   * Set the file upload support status.
   *
   * @param boolean $fileUploadSupport The file upload support status.
   *
   * @return BaseFacebook
   */
  public static  function setFileUploadSupport($fileUploadSupport) {
    self::$fileUploadSupport = $fileUploadSupport;
   // return $this;
  }

  /**
   * Get the file upload support status.
   *
   * @return boolean true if and only if the server supports file upload.
   */
  public static  function getFileUploadSupport() {
    return self::$fileUploadSupport;
  }

  /**
   * Get the file upload support status.
   *
   * @return boolean true if and only if the server supports file upload.
   *
   * @deprecated Use getFileUploadSupport instead.
   * @see getFileUploadSupport()
   */
  public static  function useFileUploadSupport() {
    return self::getFileUploadSupport();
  }

  /**
   * Sets the access token for api calls.  Use this if you get
   * your access token by other means and just want the SDK
   * to use it.
   *
   * @param string $access_token an access token.
   *
   * @return BaseFacebook
   */
  public static  function setAccessToken($access_token) {
    self::$accessToken = $access_token;
    //return $this;
  }

  /**
   * Extend an access token, while removing the short-lived token that might
   * have been generated via client-side flow. Thanks to http://bit.ly/b0Pt0H
   * for the workaround.
   */
  public  static function setExtendedAccessToken() {
    try {
      // need to circumvent json_decode by calling _oauthRequest
      // directly, since response isn't JSON format.
      $access_token_response = self::_oauthRequest(
        self::getUrl('graph', '/oauth/access_token'),
        $params = array(
          'client_id' => self::getAppId(),
          'client_secret' => self::getAppSecret(),
          'grant_type' => 'fb_exchange_token',
          'fb_exchange_token' => self::getAccessToken(),
        )
      );
    }
    catch (FacebookApiException $e) {
      // most likely that user very recently revoked authorization.
      // In any event, we don't have an access token, so say so.
      return false;
    }

    if (empty($access_token_response)) {
      return false;
    }

    $response_params = array();
    parse_str($access_token_response, $response_params);

    if (!isset($response_params['access_token'])) {
      return false;
    }

    self::destroySession();

    self::setPersistentData(
      'access_token', $response_params['access_token']
    );
  }

  /**
   * Determines the access token that should be used for API calls.
   * The first time this is called, $this->accessToken is set equal
   * to either a valid user access token, or it's set to the application
   * access token if a valid user access token wasn't available.  Subsequent
   * calls return whatever the first call returned.
   *
   * @return string The access token
   */
  public static  function getAccessToken() {
    if (self::$accessToken !== null) {
      // we've done this already and cached it.  Just return.
      return self::$accessToken;
    }

    // first establish access token to be the application
    // access token, in case we navigate to the /oauth/access_token
    // endpoint, where SOME access token is required.
    self::setAccessToken(self::getApplicationAccessToken());
    $user_access_token = self::getUserAccessToken();
    if ($user_access_token) {
      self::setAccessToken($user_access_token);
    }

    return self::$accessToken;
  }

  /**
   * Determines and returns the user access token, first using
   * the signed request if present, and then falling back on
   * the authorization code if present.  The intent is to
   * return a valid user access token, or false if one is determined
   * to not be available.
   *
   * @return string A valid user access token, or false if one
   *                could not be determined.
   */
  protected  static function getUserAccessToken() {
    // first, consider a signed request if it's supplied.
    // if there is a signed request, then it alone determines
    // the access token.
    $signed_request = self::getSignedRequest();
    if ($signed_request) {
      // apps.facebook.com hands the access_token in the signed_request
      if (array_key_exists('oauth_token', $signed_request)) {
        $access_token = $signed_request['oauth_token'];
        self::setPersistentData('access_token', $access_token);
        return $access_token;
      }

      // the JS SDK puts a code in with the redirect_uri of ''
      if (array_key_exists('code', $signed_request)) {
        $code = $signed_request['code'];
        if ($code && $code == self::getPersistentData('code')) {
          // short-circuit if the code we have is the same as the one presented
          return self::getPersistentData('access_token');
        }

        $access_token = self::getAccessTokenFromCode($code, '');
        if ($access_token) {
          self::setPersistentData('code', $code);
          self::setPersistentData('access_token', $access_token);
          return $access_token;
        }
      }

      // signed request states there's no access token, so anything
      // stored should be cleared.
      self::clearAllPersistentData();
      return false; // respect the signed request's data, even
                    // if there's an authorization code or something else
    }

    $code = self::getCode();
    if ($code && $code != self::getPersistentData('code')) {
      $access_token = self::getAccessTokenFromCode($code);
      if ($access_token) {
        self::setPersistentData('code', $code);
        self::setPersistentData('access_token', $access_token);
        return $access_token;
      }

      // code was bogus, so everything based on it should be invalidated.
      self::clearAllPersistentData();
      return false;
    }

    // as a fallback, just return whatever is in the persistent
    // store, knowing nothing explicit (signed request, authorization
    // code, etc.) was present to shadow it (or we saw a code in $_REQUEST,
    // but it's the same as what's in the persistent store)
    return self::getPersistentData('access_token');
  }

  /**
   * Retrieve the signed request, either from a request parameter or,
   * if not present, from a cookie.
   *
   * @return string the signed request, if available, or null otherwise.
   */
  public static  function getSignedRequest() {
    if (!self::$signedRequest) {
      if (self::$allowSignedRequest && !empty($_REQUEST['signed_request'])) {
        self::$signedRequest = self::parseSignedRequest(
          $_REQUEST['signed_request']
        );
      } else if (!empty($_COOKIE[self::getSignedRequestCookieName()])) {
        self::$signedRequest = self::parseSignedRequest(
          $_COOKIE[self::getSignedRequestCookieName()]);
      }
    }
    return self::$signedRequest;
  }

  /**
   * Get the UID of the connected user, or 0
   * if the Facebook user is not connected.
   *
   * @return string the UID if available.
   */
  public  static function getUser() {
    if (self::$user !== null) {
      // we've already determined this and cached the value.
      return self::$user;
    }

    return self::$user = self::getUserFromAvailableData();
  }

  /**
   * Determines the connected user by first examining any signed
   * requests, then considering an authorization code, and then
   * falling back to any persistent store storing the user.
   *
   * @return integer The id of the connected Facebook user,
   *                 or 0 if no such user exists.
   */
  protected static  function getUserFromAvailableData() {
    // if a signed request is supplied, then it solely determines
    // who the user is.
    $signed_request = self::getSignedRequest();
    if ($signed_request) {
      if (array_key_exists('user_id', $signed_request)) {
        $user = $signed_request['user_id'];

        if($user != self::getPersistentData('user_id')){
          self::clearAllPersistentData();
        }

        self::setPersistentData('user_id', $signed_request['user_id']);
        return $user;
      }

      // if the signed request didn't present a user id, then invalidate
      // all entries in any persistent store.
      self::clearAllPersistentData();
      return 0;
    }

    $user = self::getPersistentData('user_id', $default = 0);
    $persisted_access_token = self::getPersistentData('access_token');

    // use access_token to fetch user id if we have a user access_token, or if
    // the cached access token has changed.
    $access_token = self::getAccessToken();
    if ($access_token &&
        $access_token != self::getApplicationAccessToken() &&
        !($user && $persisted_access_token == $access_token)) {
      $user = self::getUserFromAccessToken();
      if ($user) {
        self::setPersistentData('user_id', $user);
      } else {
        self::clearAllPersistentData();
      }
    }

    return $user;
  }

  /**
   * Get a Login URL for use with redirects. By default, full page redirect is
   * assumed. If you are using the generated URL with a window.open() call in
   * JavaScript, you can pass in display=popup as part of the $params.
   *
   * The parameters:
   * - redirect_uri: the url to go to after a successful login
   * - scope: comma separated list of requested extended perms
   *
   * @param array $params Provide custom parameters
   * @return string The URL for the login flow
   */
  public static  function getLoginUrl($params=array()) {
    self::establishCSRFTokenState();
    $currentUrl = self::getCurrentUrl();

    // if 'scope' is passed as an array, convert to comma separated list
    $scopeParams = isset($params['scope']) ? $params['scope'] : null;
    if ($scopeParams && is_array($scopeParams)) {
      $params['scope'] = implode(',', $scopeParams);
    }

    return self::getUrl(
      'www',
      'dialog/oauth',
      array_merge(
        array(
          'client_id' => self::getAppId(),
          'redirect_uri' => $currentUrl, // possibly overwritten
          'state' => self::$state,
          'sdk' => 'php-sdk-'.self::VERSION
        ),
        $params
      ));
  }

  /**
   * Get a Logout URL suitable for use with redirects.
   *
   * The parameters:
   * - next: the url to go to after a successful logout
   *
   * @param array $params Provide custom parameters
   * @return string The URL for the logout flow
   */
  public static  function getLogoutUrl($params=array()) {
    return self::getUrl(
      'www',
      'logout.php',
      array_merge(array(
        'next' => self::getCurrentUrl(),
        'access_token' => self::getUserAccessToken(),
      ), $params)
    );
  }

  /**
   * Make an API call.
   *
   * @return mixed The decoded response
   */
  public static  function api(/* polymorphic */) {
    $args = func_get_args();
    if (is_array($args[0])) {
      return self::_restserver($args[0]);
    } else {
      //return call_user_func_array(array(\BaseFacebook, '_graph'), $args);
	  return self::_graph(implode(",",$args));
    }
  }

  /**
   * Constructs and returns the name of the cookie that
   * potentially houses the signed request for the app user.
   * The cookie is not set by the BaseFacebook class, but
   * it may be set by the JavaScript SDK.
   *
   * @return string the name of the cookie that would house
   *         the signed request value.
   */
  protected static  function getSignedRequestCookieName() {
    return 'fbsr_'.self::getAppId();
  }

  /**
   * Constructs and returns the name of the cookie that potentially contain
   * metadata. The cookie is not set by the BaseFacebook class, but it may be
   * set by the JavaScript SDK.
   *
   * @return string the name of the cookie that would house metadata.
   */
  protected static  function getMetadataCookieName() {
    return 'fbm_'.self::getAppId();
  }

  /**
   * Get the authorization code from the query parameters, if it exists,
   * and otherwise return false to signal no authorization code was
   * discoverable.
   *
   * @return mixed The authorization code, or false if the authorization
   *               code could not be determined.
   */
  protected static  function getCode() {
    if (!isset($_REQUEST['code']) || !isset($_REQUEST['state'])) {
      return false;
    }
    if (self::$state === $_REQUEST['state']) {
        // CSRF state has done its job, so clear it
        self::$state = null;
        self::clearPersistentData('state');
        return $_REQUEST['code'];
    }
    self::errorLog('CSRF state token does not match one provided.');

    return false;
  }

  /**
   * Retrieves the UID with the understanding that
   * $this->accessToken has already been set and is
   * seemingly legitimate.  It relies on Facebook's Graph API
   * to retrieve user information and then extract
   * the user ID.
   *
   * @return integer Returns the UID of the Facebook user, or 0
   *                 if the Facebook user could not be determined.
   */
  protected static  function getUserFromAccessToken() {
    try {
      $user_info = self::api('/me');
      return $user_info['id'];
    } catch (FacebookApiException $e) {
      return 0;
    }
  }

  /**
   * Returns the access token that should be used for logged out
   * users when no authorization code is available.
   *
   * @return string The application access token, useful for gathering
   *                public information about users and applications.
   */
  public static  function getApplicationAccessToken() {
    return self::$appId.'|'.self::$appSecret;
  }

  /**
   * Lays down a CSRF state token for this process.
   *
   * @return void
   */
  protected static  function establishCSRFTokenState() {
    if (self::$state === null) {
      self::$state = md5(uniqid(mt_rand(), true));
      self::setPersistentData('state', self::$state);
    }
  }

  /**
   * Retrieves an access token for the given authorization code
   * (previously generated from www.facebook.com on behalf of
   * a specific user).  The authorization code is sent to graph.facebook.com
   * and a legitimate access token is generated provided the access token
   * and the user for which it was generated all match, and the user is
   * either logged in to Facebook or has granted an offline access permission.
   *
   * @param string $code An authorization code.
   * @param string $redirect_uri Optional redirect URI. Default null
   *
   * @return mixed An access token exchanged for the authorization code, or
   *               false if an access token could not be generated.
   */
  protected static  function getAccessTokenFromCode($code, $redirect_uri = null) {
    if (empty($code)) {
      return false;
    }

    if ($redirect_uri === null) {
      $redirect_uri = self::getCurrentUrl();
    }

    try {
      // need to circumvent json_decode by calling _oauthRequest
      // directly, since response isn't JSON format.
      $access_token_response =
        self::_oauthRequest(
          self::getUrl('graph', '/oauth/access_token'),
          $params = array('client_id' => self::getAppId(),
                          'client_secret' => self::getAppSecret(),
                          'redirect_uri' => $redirect_uri,
                          'code' => $code));
    } catch (FacebookApiException $e) {
      // most likely that user very recently revoked authorization.
      // In any event, we don't have an access token, so say so.
      return false;
    }

    if (empty($access_token_response)) {
      return false;
    }

    $response_params = array();
    parse_str($access_token_response, $response_params);
    if (!isset($response_params['access_token'])) {
      return false;
    }

    return $response_params['access_token'];
  }

  /**
   * Invoke the old restserver.php endpoint.
   *
   * @param array $params Method call object
   *
   * @return mixed The decoded response object
   * @throws FacebookApiException
   */
  protected static  function _restserver($params) {
    // generic application level parameters
    $params['api_key'] = self::getAppId();
    $params['format'] = 'json-strings';

    $result = json_decode(self::_oauthRequest(
      self::getApiUrl($params['method']),
      $params
    ), true);

    // results are returned, errors are thrown
    if (is_array($result) && isset($result['error_code'])) {
      self::throwAPIException($result);
      // @codeCoverageIgnoreStart
    }
    // @codeCoverageIgnoreEnd

    $method = strtolower($params['method']);
    if ($method === 'auth.expiresession' ||
        $method === 'auth.revokeauthorization') {
      self::destroySession();
    }

    return $result;
  }

  /**
   * Return true if this is video post.
   *
   * @param string $path The path
   * @param string $method The http method (default 'GET')
   *
   * @return boolean true if this is video post
   */
  protected static  function isVideoPost($path, $method = 'GET') {
    if ($method == 'POST' && preg_match("/^(\/)(.+)(\/)(videos)$/", $path)) {
      return true;
    }
    return false;
  }

  /**
   * Invoke the Graph API.
   *
   * @param string $path The path (required)
   * @param string $method The http method (default 'GET')
   * @param array $params The query/post data
   *
   * @return mixed The decoded response object
   * @throws FacebookApiException
   */
  protected  static function _graph($path, $method = 'GET', $params = array()) {
    if (is_array($method) && empty($params)) {
      $params = $method;
      $method = 'GET';
    }
    $params['method'] = $method; // method override as we always do a POST

    if (self::isVideoPost($path, $method)) {
      $domainKey = 'graph_video';
    } else {
      $domainKey = 'graph';
    }

    $result = json_decode(self::_oauthRequest(
      self::getUrl($domainKey, $path),
      $params
    ), true);

    // results are returned, errors are thrown
    if (is_array($result) && isset($result['error'])) {
      self::throwAPIException($result);
      // @codeCoverageIgnoreStart
    }
    // @codeCoverageIgnoreEnd

    return $result;
  }

  /**
   * Make a OAuth Request.
   *
   * @param string $url The path (required)
   * @param array $params The query/post data
   *
   * @return string The decoded response object
   * @throws FacebookApiException
   */
  protected static  function _oauthRequest($url, $params) {
    if (!isset($params['access_token'])) {
      $params['access_token'] = self::getAccessToken();
    }

    if (isset($params['access_token']) && !isset($params['appsecret_proof'])) {
      $params['appsecret_proof'] = self::getAppSecretProof($params['access_token']);
    }

    // json_encode all params values that are not strings
    foreach ($params as $key => $value) {
      if (!is_string($value) && !($value instanceof CURLFile)) {
        $params[$key] = json_encode($value);
      }
    }

    return self::makeRequest($url, $params);
  }

  /**
   * Generate a proof of App Secret
   * This is required for all API calls originating from a server
   * It is a sha256 hash of the access_token made using the app secret
   *
   * @param string $access_token The access_token to be hashed (required)
   *
   * @return string The sha256 hash of the access_token
   */
  protected static  function getAppSecretProof($access_token) {
    return hash_hmac('sha256', $access_token, self::getAppSecret());
  }

  /**
   * Makes an HTTP request. This method can be overridden by subclasses if
   * developers want to do fancier things or use something other than curl to
   * make the request.
   *
   * @param string $url The URL to make the request to
   * @param array $params The parameters to use for the POST body
   * @param CurlHandler $ch Initialized curl handle
   *
   * @return string The response text
   */
  protected static  function makeRequest($url, $params, $ch=null) {
    if (!$ch) {
      $ch = curl_init();
    }

    $opts = self::$CURL_OPTS;
    if (self::getFileUploadSupport()) {
      $opts[CURLOPT_POSTFIELDS] = $params;
    } else {
      $opts[CURLOPT_POSTFIELDS] = http_build_query($params, null, '&');
    }
    $opts[CURLOPT_URL] = $url;

    // disable the 'Expect: 100-continue' behaviour. This causes CURL to wait
    // for 2 seconds if the server does not support this header.
    if (isset($opts[CURLOPT_HTTPHEADER])) {
      $existing_headers = $opts[CURLOPT_HTTPHEADER];
      $existing_headers[] = 'Expect:';
      $opts[CURLOPT_HTTPHEADER] = $existing_headers;
    } else {
      $opts[CURLOPT_HTTPHEADER] = array('Expect:');
    }

    curl_setopt_array($ch, $opts);
    $result = curl_exec($ch);

    $errno = curl_errno($ch);
    // CURLE_SSL_CACERT || CURLE_SSL_CACERT_BADFILE
    if ($errno == 60 || $errno == 77) {
      self::errorLog('Invalid or no certificate authority found, '.
                     'using bundled information');
      curl_setopt($ch, CURLOPT_CAINFO,
                  dirname(__FILE__) . DIRECTORY_SEPARATOR . 'fb_ca_chain_bundle.crt');
      $result = curl_exec($ch);
    }

    // With dual stacked DNS responses, it's possible for a server to
    // have IPv6 enabled but not have IPv6 connectivity.  If this is
    // the case, curl will try IPv4 first and if that fails, then it will
    // fall back to IPv6 and the error EHOSTUNREACH is returned by the
    // operating system.
    if ($result === false && empty($opts[CURLOPT_IPRESOLVE])) {
        $matches = array();
        $regex = '/Failed to connect to ([^:].*): Network is unreachable/';
        if (preg_match($regex, curl_error($ch), $matches)) {
          if (strlen(@inet_pton($matches[1])) === 16) {
            self::errorLog('Invalid IPv6 configuration on server, '.
                           'Please disable or get native IPv6 on your server.');
            self::$CURL_OPTS[CURLOPT_IPRESOLVE] = CURL_IPRESOLVE_V4;
            curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
            $result = curl_exec($ch);
          }
        }
    }

    if ($result === false) {
      $e = new FacebookApiException(array(
        'error_code' => curl_errno($ch),
        'error' => array(
        'message' => curl_error($ch),
        'type' => 'CurlException',
        ),
      ));
      curl_close($ch);
      throw $e;
    }
    curl_close($ch);
    return $result;
  }

  /**
   * Parses a signed_request and validates the signature.
   *
   * @param string $signed_request A signed token
   *
   * @return array The payload inside it or null if the sig is wrong
   */
  protected static  function parseSignedRequest($signed_request) {

    if (!$signed_request || strpos($signed_request, '.') === false) {
        self::errorLog('Signed request was invalid!');
        return null;
    }

    list($encoded_sig, $payload) = explode('.', $signed_request, 2);

    // decode the data
    $sig = self::base64UrlDecode($encoded_sig);
    $data = json_decode(self::base64UrlDecode($payload), true);

    if (!isset($data['algorithm'])
        || strtoupper($data['algorithm']) !==  self::SIGNED_REQUEST_ALGORITHM
    ) {
      self::errorLog(
        'Unknown algorithm. Expected ' . self::SIGNED_REQUEST_ALGORITHM);
      return null;
    }

    // check sig
    $expected_sig = hash_hmac('sha256', $payload,
                              self::getAppSecret(), $raw = true);

    if (strlen($expected_sig) !== strlen($sig)) {
      self::errorLog('Bad Signed JSON signature!');
      return null;
    }

    $result = 0;
    for ($i = 0; $i < strlen($expected_sig); $i++) {
      $result |= ord($expected_sig[$i]) ^ ord($sig[$i]);
    }

    if ($result == 0) {
      return $data;
    } else {
      self::errorLog('Bad Signed JSON signature!');
      return null;
    }
  }

  /**
   * Makes a signed_request blob using the given data.
   *
   * @param array $data The data array.
   *
   * @return string The signed request.
   */
  protected static  function makeSignedRequest($data) {
    if (!is_array($data)) {
      throw new InvalidArgumentException(
        'makeSignedRequest expects an array. Got: ' . print_r($data, true));
    }
    $data['algorithm'] = self::SIGNED_REQUEST_ALGORITHM;
    $data['issued_at'] = time();
    $json = json_encode($data);
    $b64 = self::base64UrlEncode($json);
    $raw_sig = hash_hmac('sha256', $b64, self::getAppSecret(), $raw = true);
    $sig = self::base64UrlEncode($raw_sig);
    return $sig.'.'.$b64;
  }

  /**
   * Build the URL for api given parameters.
   *
   * @param string $method The method name.
   *
   * @return string The URL for the given parameters
   */
  protected static  function getApiUrl($method) {
    static $READ_ONLY_CALLS =
      array('admin.getallocation' => 1,
            'admin.getappproperties' => 1,
            'admin.getbannedusers' => 1,
            'admin.getlivestreamvialink' => 1,
            'admin.getmetrics' => 1,
            'admin.getrestrictioninfo' => 1,
            'application.getpublicinfo' => 1,
            'auth.getapppublickey' => 1,
            'auth.getsession' => 1,
            'auth.getsignedpublicsessiondata' => 1,
            'comments.get' => 1,
            'connect.getunconnectedfriendscount' => 1,
            'dashboard.getactivity' => 1,
            'dashboard.getcount' => 1,
            'dashboard.getglobalnews' => 1,
            'dashboard.getnews' => 1,
            'dashboard.multigetcount' => 1,
            'dashboard.multigetnews' => 1,
            'data.getcookies' => 1,
            'events.get' => 1,
            'events.getmembers' => 1,
            'fbml.getcustomtags' => 1,
            'feed.getappfriendstories' => 1,
            'feed.getregisteredtemplatebundlebyid' => 1,
            'feed.getregisteredtemplatebundles' => 1,
            'fql.multiquery' => 1,
            'fql.query' => 1,
            'friends.arefriends' => 1,
            'friends.get' => 1,
            'friends.getappusers' => 1,
            'friends.getlists' => 1,
            'friends.getmutualfriends' => 1,
            'gifts.get' => 1,
            'groups.get' => 1,
            'groups.getmembers' => 1,
            'intl.gettranslations' => 1,
            'links.get' => 1,
            'notes.get' => 1,
            'notifications.get' => 1,
            'pages.getinfo' => 1,
            'pages.isadmin' => 1,
            'pages.isappadded' => 1,
            'pages.isfan' => 1,
            'permissions.checkavailableapiaccess' => 1,
            'permissions.checkgrantedapiaccess' => 1,
            'photos.get' => 1,
            'photos.getalbums' => 1,
            'photos.gettags' => 1,
            'profile.getinfo' => 1,
            'profile.getinfooptions' => 1,
            'stream.get' => 1,
            'stream.getcomments' => 1,
            'stream.getfilters' => 1,
            'users.getinfo' => 1,
            'users.getloggedinuser' => 1,
            'users.getstandardinfo' => 1,
            'users.hasapppermission' => 1,
            'users.isappuser' => 1,
            'users.isverified' => 1,
            'video.getuploadlimits' => 1);
    $name = 'api';
    if (isset($READ_ONLY_CALLS[strtolower($method)])) {
      $name = 'api_read';
    } else if (strtolower($method) == 'video.upload') {
      $name = 'api_video';
    }
    return self::getUrl($name, 'restserver.php');
  }

  /**
   * Build the URL for given domain alias, path and parameters.
   *
   * @param string $name   The name of the domain
   * @param string $path   Optional path (without a leading slash)
   * @param array  $params Optional query parameters
   *
   * @return string The URL for the given parameters
   */
  protected static  function getUrl($name, $path='', $params=array()) {
    $url = self::$DOMAIN_MAP[$name];
    if ($path) {
      if ($path[0] === '/') {
        $path = substr($path, 1);
      }
      $url .= $path;
    }
    if ($params) {
      $url .= '?' . http_build_query($params, null, '&');
    }

    return $url;
  }

  /**
   * Returns the HTTP Host
   *
   * @return string The HTTP Host
   */
  protected static  function getHttpHost() {
    if (self::$trustForwarded && isset($_SERVER['HTTP_X_FORWARDED_HOST'])) {
      $forwardProxies = explode(',', $_SERVER['HTTP_X_FORWARDED_HOST']);
      if (!empty($forwardProxies)) {
        return $forwardProxies[0];
      }
    }
    return $_SERVER['HTTP_HOST'];
  }

  /**
   * Returns the HTTP Protocol
   *
   * @return string The HTTP Protocol
   */
  protected static  function getHttpProtocol() {
    if (self::$trustForwarded && isset($_SERVER['HTTP_X_FORWARDED_PROTO'])) {
      if ($_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https') {
        return 'https';
      }
      return 'http';
    }
    /*apache + variants specific way of checking for https*/
    if (isset($_SERVER['HTTPS']) &&
        ($_SERVER['HTTPS'] === 'on' || $_SERVER['HTTPS'] == 1)) {
      return 'https';
    }
    /*nginx way of checking for https*/
    if (isset($_SERVER['SERVER_PORT']) &&
        ($_SERVER['SERVER_PORT'] === '443')) {
      return 'https';
    }
    return 'http';
  }

  /**
   * Returns the base domain used for the cookie.
   *
   * @return string The base domain
   */
  protected static  function getBaseDomain() {
    // The base domain is stored in the metadata cookie if not we fallback
    // to the current hostname
    $metadata = self::getMetadataCookie();
    if (array_key_exists('base_domain', $metadata) &&
        !empty($metadata['base_domain'])) {
      return trim($metadata['base_domain'], '.');
    }
    return self::getHttpHost();
  }

  /**
   * Returns the Current URL, stripping it of known FB parameters that should
   * not persist.
   *
   * @return string The current URL
   */
  protected static  function getCurrentUrl() {
    $protocol = self::getHttpProtocol() . '://';
    $host = self::getHttpHost();
    $currentUrl = $protocol.$host.$_SERVER['REQUEST_URI'];
    $parts = parse_url($currentUrl);

    $query = '';
    if (!empty($parts['query'])) {
      // drop known fb params
      $params = explode('&', $parts['query']);
      $retained_params = array();
      foreach ($params as $param) {
        if (self::shouldRetainParam($param)) {
          $retained_params[] = $param;
        }
      }

      if (!empty($retained_params)) {
        $query = '?'.implode($retained_params, '&');
      }
    }

    // use port if non default
    $port =
      isset($parts['port']) &&
      (($protocol === 'http://' && $parts['port'] !== 80) ||
       ($protocol === 'https://' && $parts['port'] !== 443))
      ? ':' . $parts['port'] : '';

    // rebuild
    return $protocol . $parts['host'] . $port . $parts['path'] . $query;
  }

  /**
   * Returns true if and only if the key or key/value pair should
   * be retained as part of the query string.  This amounts to
   * a brute-force search of the very small list of Facebook-specific
   * params that should be stripped out.
   *
   * @param string $param A key or key/value pair within a URL's query (e.g.
   *                      'foo=a', 'foo=', or 'foo'.
   *
   * @return boolean
   */
  protected  static function shouldRetainParam($param) {
    foreach (self::$DROP_QUERY_PARAMS as $drop_query_param) {
      if ($param === $drop_query_param ||
          strpos($param, $drop_query_param.'=') === 0) {
        return false;
      }
    }

    return true;
  }

  /**
   * Analyzes the supplied result to see if it was thrown
   * because the access token is no longer valid.  If that is
   * the case, then we destroy the session.
   *
   * @param array $result A record storing the error message returned
   *                      by a failed API call.
   */
  protected  static function throwAPIException($result) {
    $e = new FacebookApiException($result);
    switch ($e->getType()) {
      // OAuth 2.0 Draft 00 style
      case 'OAuthException':
        // OAuth 2.0 Draft 10 style
      case 'invalid_token':
        // REST server errors are just Exceptions
      case 'Exception':
        $message = $e->getMessage();
        if ((strpos($message, 'Error validating access token') !== false) ||
            (strpos($message, 'Invalid OAuth access token') !== false) ||
            (strpos($message, 'An active access token must be used') !== false)
        ) {
          self::destroySession();
        }
        break;
    }

    throw $e;
  }


  /**
   * Prints to the error log if you aren't in command line mode.
   *
   * @param string $msg Log message
   */
  protected static function errorLog($msg) {
    // disable error log if we are running in a CLI environment
    // @codeCoverageIgnoreStart
    if (php_sapi_name() != 'cli') {
      error_log($msg);
    }
    // uncomment this if you want to see the errors on the page
    // print 'error_log: '.$msg."\n";
    // @codeCoverageIgnoreEnd
  }

  /**
   * Base64 encoding that doesn't need to be urlencode()ed.
   * Exactly the same as base64_encode except it uses
   *   - instead of +
   *   _ instead of /
   *   No padded =
   *
   * @param string $input base64UrlEncoded input
   *
   * @return string The decoded string
   */
  protected static function base64UrlDecode($input) {
    return base64_decode(strtr($input, '-_', '+/'));
  }

  /**
   * Base64 encoding that doesn't need to be urlencode()ed.
   * Exactly the same as base64_encode except it uses
   *   - instead of +
   *   _ instead of /
   *
   * @param string $input The input to encode
   * @return string The base64Url encoded input, as a string.
   */
  protected static function base64UrlEncode($input) {
    $str = strtr(base64_encode($input), '+/', '-_');
    $str = str_replace('=', '', $str);
    return $str;
  }

  /**
   * Destroy the current session
   */
  public static  function destroySession() {
    self::$accessToken = null;
    self::$signedRequest = null;
    self::$user = null;
    self::clearAllPersistentData();

    // Javascript sets a cookie that will be used in getSignedRequest that we
    // need to clear if we can
    $cookie_name = self::getSignedRequestCookieName();
    if (array_key_exists($cookie_name, $_COOKIE)) {
      unset($_COOKIE[$cookie_name]);
      if (!headers_sent()) {
        $base_domain = self::getBaseDomain();
        setcookie($cookie_name, '', 1, '/', '.'.$base_domain);
      } else {
        // @codeCoverageIgnoreStart
        self::errorLog(
          'There exists a cookie that we wanted to clear that we couldn\'t '.
          'clear because headers was already sent. Make sure to do the first '.
          'API call before outputing anything.'
        );
        // @codeCoverageIgnoreEnd
      }
    }
  }

  /**
   * Parses the metadata cookie that our Javascript API set
   *
   * @return array an array mapping key to value
   */
  protected static  function getMetadataCookie() {
    $cookie_name = self::getMetadataCookieName();
    if (!array_key_exists($cookie_name, $_COOKIE)) {
      return array();
    }

    // The cookie value can be wrapped in "-characters so remove them
    $cookie_value = trim($_COOKIE[$cookie_name], '"');

    if (empty($cookie_value)) {
      return array();
    }

    $parts = explode('&', $cookie_value);
    $metadata = array();
    foreach ($parts as $part) {
      $pair = explode('=', $part, 2);
      if (!empty($pair[0])) {
        $metadata[urldecode($pair[0])] =
          (count($pair) > 1) ? urldecode($pair[1]) : '';
      }
    }

    return $metadata;
  }

  /**
   * Finds whether the given domain is allowed or not
   *
   * @param string $big   The value to be checked against $small
   * @param string $small The input string
   *
   * @return boolean Returns TRUE if $big matches $small
   */
  protected static function isAllowedDomain($big, $small) {
    if ($big === $small) {
      return true;
    }
    return self::endsWith($big, '.'.$small);
  }

  /**
   * Checks if $big string ends with $small string
   *
   * @param string $big   The value to be checked against $small
   * @param string $small The input string
   *
   * @return boolean TRUE if $big ends with $small
   */
  protected static function endsWith($big, $small) {
    $len = strlen($small);
    if ($len === 0) {
      return true;
    }
    return substr($big, -$len) === $small;
  }

  /**
   * Each of the following four methods should be overridden in
   * a concrete subclass, as they are in the provided Facebook class.
   * The Facebook class uses PHP sessions to provide a primitive
   * persistent store, but another subclass--one that you implement--
   * might use a database, memcache, or an in-memory cache.
   *
   * @see Facebook
   */

  /**
   * Stores the given ($key, $value) pair, so that future calls to
   * getPersistentData($key) return $value. This call may be in another request.
   *
   * @param string $key
   * @param array $value
   *
   * @return void
   */
  //abstract protected  function setPersistentData($key, $value);
   protected static function setPersistentData($key, $value) {
    if (!in_array($key, self::$kSupportedKeys)) {
      self::errorLog('Unsupported key passed to setPersistentData.');
      return;
    }

    $session_var_name = self::constructSessionVariableName($key);
    $_SESSION[$session_var_name] = $value;
  }

  /**
   * Get the data for $key, persisted by BaseFacebook::setPersistentData()
   *
   * @param string $key The key of the data to retrieve
   * @param boolean $default The default value to return if $key is not found
   *
   * @return mixed
   */
  //abstract protected function  getPersistentData($key, $default = false);
   protected static function getPersistentData($key, $default = false) {
    if (!in_array($key, self::$kSupportedKeys)) {
      self::errorLog('Unsupported key passed to getPersistentData.');
      return $default;
    }

    $session_var_name = self::constructSessionVariableName($key);
    return isset($_SESSION[$session_var_name]) ?
      $_SESSION[$session_var_name] : $default;
  }


  /**
   * Clear the data with $key from the persistent storage
   *
   * @param string $key
   *
   * @return void
   */
 // abstract protected  function clearPersistentData($key);

  /**
   * Clear all data from the persistent storage
   *
   * @return void
   */
  //abstract protected  function clearAllPersistentData();
  protected static function clearPersistentData($key) {
    if (!in_array($key, self::$kSupportedKeys)) {
      self::errorLog('Unsupported key passed to clearPersistentData.');
      return;
    }

    $session_var_name = self::constructSessionVariableName($key);
    unset($_SESSION[$session_var_name]);
  }

  protected static function clearAllPersistentData() {
    foreach (self::$kSupportedKeys as $key) {
      self::clearPersistentData($key);
    }
    if (self::$sharedSessionID) {
      self::deleteSharedSessionCookie();
    }
  }
  
  protected static function deleteSharedSessionCookie() {
    $cookie_name = self::getSharedSessionCookieName();
    unset($_COOKIE[$cookie_name]);
    $base_domain = self::getBaseDomain();
    setcookie($cookie_name, '', 1, '/', '.'.$base_domain);
  }

  protected static function getSharedSessionCookieName() {
    return self::FBSS_COOKIE_NAME . '_' . self::getAppId();
  }
  
  protected static function constructSessionVariableName($key) {
    $parts = array('fb', self::getAppId(), $key);
    if (self::$sharedSessionID) {
      array_unshift($parts, self::$sharedSessionID);
    }
    return implode('_', $parts);
  }
  
  protected static $kSupportedKeys =
    array('state', 'code', 'access_token', 'user_id');
  
}
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class GCDataStore
{
  /**
   * [PACKAGE description]
   * @var string
   */
  const PACKAGE = 'francis94c/ci-gc-datastore';

  /**
   * [private description]
   * @var [type]
   */
  private $ci;

  /**
   * [private description]
   * @var [type]
   */
  private $projectId = null;

  /**
   * [private description]
   * @var [type]
   */
  private $accessToken = null;

  /**
   * [private description]
   * @var [type]
   */
  private $accessTokenType = null;

  /**
   * [private description]
   * @var [type]
   */
  private $accessTokenExpiresAt = null;

  /**
   * [__construct description]
   * @date 2020-03-14
   */
  function __construct(?array $params=null)
  {
    $this->ci =& get_instance();
    $this->ci->load->splint(self::PACKAGE, '%base64');
    $this->ci->load->splint(self::PACKAGE, '%gcd');
    splint_autoload_register(self::PACKAGE);

    if ($params) {
      $this->projectId = $params['project_id'] ?? null;
    }
  }

  /**
   * [setAccessToken description]
   * @date  2020-02-21
   * @param string     $accessToken [description]
   */
  public function setAccessToken(string $accessToken):void
  {
    $this->accessToken = $accessToken;
  }

  /**
   * [setProjectId description]
   * @date  2020-03-14
   * @param string     $projectId [description]
   */
  public function setProjectId(string $projectId):void
  {
    $this->projectId = $projectId;
  }

  /**
   * [getProjectId description]
   * @date   2020-03-15
   * @return string     [description]
   */
  public function getProjectId():string
  {
    return $this->projectId;
  }

  /**
   * [fetch_access_token description]
   * @date   2020-02-22
   * @return string     [description]
   */
  private function fetch_access_token():void
  {
    if (!is_file(APPPATH.'/config/google-services.json')) {
      throw new Exception('Credentials not found.');
    }
    $credentials = json_decode(file_get_contents(APPPATH.'/config/google-services.json'));

    $jwtHeader = [
      'alg' => 'RS256',
      'typ' => 'JWT',
    ];

    $jwtClaimSet = [
      'iss'   => $credentials->client_email,
      'scope' => 'https://www.googleapis.com/auth/datastore',
      'aud'   => 'https://oauth2.googleapis.com/token',
      'exp'   => strtotime('+30 Minutes'),
      'iat'   => time()
    ];

    $jwt = base64url_encode(json_encode($jwtHeader)) . '.' . base64url_encode(json_encode($jwtClaimSet));
    base64url_encode(openssl_sign($jwt, $signature, $credentials->private_key, "SHA256"));
    $signature = base64_encode($signature);

    $ch = curl_init('https://oauth2.googleapis.com/token');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    if (ENVIRONMENT == 'development') {
      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    }
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
      'Content-Type: application/x-www-form-urlencoded'
    ]);
    curl_setopt($ch, CURLOPT_USERAGENT, 'CI-FCM-LIB');
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
      'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
      'assertion'  => $jwt . '.' . $signature,
    ]));

    $response = curl_exec($ch);
    $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($code == 400) {
      throw new Exception("Google OAuth Error: $response");
    }

    if ($code != 200) {
      throw new Exception("Unknown Google OAuth Error: $response");
    }

    $response = json_decode($response);
    $this->accessToken = $response->access_token;
    $this->accessTokenType = $response->token_type;
    $this->accessTokenExpiresAt = time() + $response->expires_in;
  }

  /**
   * [get_base_url description]
   * @date   2020-02-17
   * @return string     [description]
   */
  private function get_base_url(string $suffix=''):string
  {
    if ($this->projectId == null) throw new Exception("FireBase Project ID not set!");
    return "https://datastore.googleapis.com/v1/projects/$this->projectId:$suffix";
  }

  /**
   * [should_fetch_access_token description]
   * @date   2020-02-22
   * @return bool       [description]
   */
  private function should_fetch_access_token():bool
  {
    return !$this->accessToken || time() >= $this->accessTokenExpiresAt;
  }

  /**
   * [commit description]
   * @date   2020-03-15
   * @param  GCDataStoreCommit $commit [description]
   * @return bool                          [description]
   */
  public function commit(GCDataStoreCommit $commit):bool
  {
    if ($this->should_fetch_access_token()) $this->fetch_access_token();

    list($code, $response) = (new GCDataStoreRequest(GCDataStoreRequest::POST))(
      $this->get_base_url('commit'),
      [
        "Authorization: $this->accessTokenType $this->accessToken",
        "ContentType: application/json"
      ],
      $commit->toArray()
    );

    var_dump($code);
    var_dump($response);
  }
}

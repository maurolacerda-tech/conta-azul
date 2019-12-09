<?php
class contaazulModulo
{
    private $client_id;
    private $client_secret;
    private $redirect_uri;
    private $auth_endpoint;
    private $scope;
    private $state;

    protected $access_token;
    protected $expires_in;
    protected $refresh_token;
    protected $date_register;
    

    public function __construct()
    { 
        $this->client_id = '6dq4uiWVUey2HoklHHpwqpuh4O5cgJRB';
        $this->client_secret = 'Z2jcgOrW47w90rpGbM0GLy4vzmoWA4Nm';
        $this->redirect_uri = 'https://cleverweb.com.br/projetos/conta-azul';
        $this->auth_endpoint = 'https://api.contaazul.com/auth/authorize';
        $this->scope = "Sale";
        $this->state = $this->random(16);
    }

    private function requestServerPost($params, $endpoint)
    {
        $autorizacao = $this->client_id.':'.$this->client_secret;
        $curl = curl_init();
        curl_setopt_array($curl, array(
          CURLOPT_URL => $endpoint,
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => "",
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 30,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => "POST",
          CURLOPT_POSTFIELDS => $params,
          CURLOPT_HTTPHEADER => array(
            "Authorization: Basic ".base64_encode($autorizacao),
          )
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if($err){
            return "cURL Error #:" . $err;
        }else{
            //var_dump($response);
            $responseToken = json_decode($response);

            $access_token = (isset($responseToken->access_token) ? $responseToken->access_token : '');
            $token_type = (isset($responseToken->token_type) ? $responseToken->token_type : '');
            $expires_in = (isset($responseToken->expires_in) ? $responseToken->expires_in : '');
            $refresh_token = (isset($responseToken->refresh_token) ? $responseToken->refresh_token : '');
            $error = (isset($responseToken->error) ? $responseToken->error : ''); 
            $error_description = (isset($responseToken->error_description) ? $responseToken->error_description : ''); 

            if($error!=''){
                return $error.': '.$error_description;
            }else{
                $this->access_token = $access_token;
                $this->expires_in = $expires_in;
                $this->refresh_token = $refresh_token;
                $this->date_register = date('Y-m-d H:i:s');

                if(array_key_exists("redirect_uri", $params)) {
                $redirect = function($url) {
                    header('location: '.$url);
                };
                $this->setTimeout($redirect($params['redirect_uri']), 3000);
                }
            }
        }
    }
    private function setTimeout($fn, $timeout){
        // sleep for $timeout milliseconds.
        sleep(($timeout/1000));
        $fn();
    }

    public function initialToken($code)
    {      
      //var_dump($params);
      $redirect_uri = $this->redirect_uri;
      $params = ['code'=>$code, 'grant_type'=>'authorization_code', 'redirect_uri'=>$redirect_uri];
      //$params2 = "?grant_type=authorization_code&redirect_uri={$redirect_uri}&code={$code}";
      return $this->requestServerPost($params, 'https://api.contaazul.com/oauth2/token');
    }

    public function getState(){
        return $this->state;
    }

    public function getUrlAuth(){
        return $this->auth_endpoint.'?redirect_uri='.urlencode($this->redirect_uri).'&client_id='.$this->client_id.'&scope=sales&state='.$this->state;
    }

    public function getAccessToken()
    {
        return $this->access_token;
    }

    public function getExpiresIn()
    {
        return $this->expires_in;
    }

    public function getRefreshToken()
    {
        return $this->refresh_token;
    }

    public function getDateRegister()
    {
        return $this->date_register;
    }

    private function random($len)
    {
        if (function_exists('openssl_random_pseudo_bytes')) {
            $byteLen = intval(($len / 2) + 1);
            $return = substr(bin2hex(openssl_random_pseudo_bytes($byteLen)), 0, $len);
        } elseif (@is_readable('/dev/urandom')) {
		$f=fopen('/dev/urandom', 'r');
		$urandom=fread($f, $len);
		fclose($f);
                $return = '';
        }

        if (empty($return)) {
            for ($i=0;$i<$len;++$i) {
                if (!isset($urandom)) {
                    if ($i%2==0) {
                        mt_srand(time()%2147 * 1000000 + (double)microtime() * 1000000);
                    }
                    $rand=48+mt_rand()%64;
                } else {
                    $rand=48+ord($urandom[$i])%64;
                }

                if ($rand>57)
                    $rand+=7;
                if ($rand>90)
                    $rand+=6;

                if ($rand==123) $rand=52;
                if ($rand==124) $rand=53;
                $return.=chr($rand);
            }
        }
	    return $return;
    }

    

}

?>
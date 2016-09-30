<?php

namespace traversient\yii;
use yii\base\Component;
use yii\base\InvalidCallException;
use yii\httpclient\Client;

/**
 * This is just an example.
 */
class FacebookAccountKit extends Component
{

    protected $myConfig;
    protected $apiClient;
    protected $app_accesstoken;
    protected $appsecret_proof;

    public function __construct(array $config)
    {
        parent::__construct($config);
        $this->myConfig = $config;
        $this->apiClient = new Client([
            'baseUrl' => 'https://graph.accountkit.com/v1.0/',
            'responseConfig' => [
                'format' => Client::FORMAT_JSON
            ],
        ]);

        if (empty($this->myConfig)){
            throw new InvalidCallException("Config must be passed!");
        }
        if (empty($this->myConfig['app_id'])){
            throw new InvalidCallException("app_id must be present in config array!");
        }
        if (empty($this->myConfig['client_id'])){
            throw new InvalidCallException("client_id must be present in config array!");
        }
        if (empty($this->myConfig['app_secret'])){
            throw new InvalidCallException("app_secret must be present in config array!");
        }

        $this->app_accesstoken = 'AA|'.$this->myConfig['app_id'].'|'.$this->myConfig['app_secret'];
        $this->appsecret_proof = hash_hmac('sha256', $this->app_accesstoken, $this->myConfig['app_secret']);
    }


    /**
     * Retrieve the access_token for the user authorization code provided by facebook sdk
     * @param string $code
     * @return string
     */
    public function getAccessTokenForCode(string $code){
        $response_accesstoken = $this->apiClient->get('access_token',[
            'code'=>$code,
            'grant_type'=>'authorization_code',
            'access_token'=>$this->app_accesstoken,
        ])->send();
        \Yii::info('Access token response:' . $response_accesstoken);
        return $response_accesstoken['access_token'];
    }
}

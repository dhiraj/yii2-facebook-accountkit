<?php

namespace traversient\yii;
use yii\base\Component;
use yii\base\InvalidCallException;
use yii\helpers\VarDumper;
use yii\httpclient\Client;
use yii\web\ServerErrorHttpException;

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
        parent::__construct();
        $this->myConfig = $config;
        $this->apiClient = new Client([
            'transport' => 'yii\httpclient\CurlTransport',
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
     * Retrieve the access_token response for the user authorization code provided
     * by facebook accountkit sdk on native app
     * @param string $code
     * @return array
     */
    public function getAccessTokenForCode(string $code){
        $response_accesstoken = $this->apiClient->get('access_token',[
            'code'=>$code,
            'grant_type'=>'authorization_code',
            'access_token'=>$this->app_accesstoken,
        ])->send();
        if (! $response_accesstoken->getIsOk()){
            $imploded = VarDumper::dumpAsString($response_accesstoken->getData());
            throw new ServerErrorHttpException("Did not get ok response, data: {$imploded}");
        }
        $responseData = $response_accesstoken->getData();
        return $responseData;
    }

    public function getUserInformation(string $accessToken){
        $response_me = $this->apiClient->get('me',[
            'access_token'=>$accessToken,
            'appsecret_proof'=> hash_hmac('sha256', $accessToken, $this->myConfig['app_secret']),
        ])->send();
        if (! $response_me->getIsOk()){
            $imploded = VarDumper::dumpAsString($response_me->getData());
            throw new ServerErrorHttpException("Did not get ok response, data: {$imploded}");
        }
        $responseData = $response_me->getData();
        return $responseData;
    }
}

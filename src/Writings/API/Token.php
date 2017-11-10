<?php

namespace Egwk\Install\Writings\API;

use Illuminate\Support\Facades\Redis;
use Egwk\Install\Writings\API;

/**
 * Description of Token
 *
 * @author Peter
 */
class Token
{

    const TOKEN_URL = 'https://cpanel.egwwritings.org/o/token/';

    /**
     * 
     * @var API API object
     */
    protected $api = null;

    /**
     * Class constructor
     *
     * @access public
     * @param API $api EGWWritings API object
     * @return void
     */
    public function __construct(API $api)
    {
        $this->api = $api;
    }

    /**
     * Gets API
     *
     * @access public
     * @return API EGWWritings API object
     */
    public function getAPI(): API
    {
        return $this->api;
    }

    /**
     * Gets Access Token from Redis, or Requests a new one if not exists or expired
     *
     * @access public
     * @return string Access Token
     */
    public function getAccessToken(): string
    {
        if (null === Redis::get('egwk:egwwritings:token'))
        {
            $responseObject = $this->requestAccessToken();
            $this->storeAccessToken($responseObject);
        }
        return Redis::get('egwk:egwwritings:token');
    }

    /**
     * Stores Access Token in Redis
     *
     * @access public
     * @param StdClass $tokenObject StdClass Token Request result
     * @return void
     */
    public function storeAccessToken($tokenObject)
    {
        if (isset($tokenObject->access_token))
        {
            Redis::set('egwk:egwwritings:token', $tokenObject->access_token);
            Redis::command('expire', ['egwk:egwwritings:token', $tokenObject->expires_in]);
        }
    }

    /**
     * Requests Access Token
     *
     * @access protected
     * @return StdClass Token Request result
     */
    protected function requestAccessToken()
    {
        return $this->getAPI()->request('POST', config('install.token_url', self::TOKEN_URL), [
                    'form_params' => [
                        'client_id'     => env('EGWWRITINGS_KEY'),
                        'client_secret' => env('EGWWRITINGS_SECRET'),
                        'redirect_uri'  => env('EGWWRITINGS_REDIRECT_URI'),
                        'grant_type'    => 'client_credentials',
                        'response_type' => 'id_token',
                    ],
                    'headers'     => [
                    ]
        ]);
    }

}

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
     * @var API $api
     */
    protected $api = null;

    public function __construct(API $api)
        {
        $this->api = $api;
        }

    public function getAPI(): API
        {
        return $this->api;
        }

    public function getAccessToken(): string
        {
        if (null === Redis::get('egwk:egwwritings:token'))
            {
            $responseObject = $this->requestAccessToken();
            if (isset($responseObject->access_token))
                {
                Redis::set('egwk:egwwritings:token', $responseObject->access_token);
                Redis::command('expire', ['egwk:egwwritings:token', $responseObject->expires_in]);
                }
            }
        return Redis::get('egwk:egwwritings:token');
        }

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

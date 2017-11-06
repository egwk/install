<?php

namespace Egwk\Install\Writings;

use GuzzleHttp;

/**
 * Description of API
 *
 * @author Peter
 */
class API
    {

    protected $client = null;

    public function __construct()
        {
        $this->client = new GuzzleHttp\Client();
        }

    public function request($verb, $url, $parameters = [])
        {
        $response = $this->client->request($verb, $url, $parameters);
        if (200 == $response->getStatusCode() && in_array('application/json', $response->getHeader('content-type')))
            {
            return json_decode($response->getBody());
            }
        return null;
        }

    }

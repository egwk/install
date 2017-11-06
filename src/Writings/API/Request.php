<?php

namespace Egwk\Install\Writings\API;

use Egwk\Install\Writings\API\Token;

/**
 * Description of Request
 *
 * @author Peter
 */
class Request extends Token
    {

    const API_URL = 'https://a.egwwritings.org/';

    public function get($command, $parameters = [])
        {
        return $this->apiRequest('GET', $command, $parameters);
        }

    protected function apiRequest($verb, $command, $parameters = [])
        {
        $token = $this->getAccessToken();
        if (null !== $token)
            {
            $parameters['query'] = array_merge(array_get($parameters, 'query', []), ['access_token' => $token]);
            return $this->getAPI()->request($verb, config('install.api_url', self::API_URL) . $command, $parameters);
            }
        return null;
        }

    }

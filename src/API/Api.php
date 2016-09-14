<?php

/**
 * Copyright (c) 2016. Cogative LTD
 */

namespace Enverido\API;

use GuzzleHttp\Client;
use Psr\Http\Message\ResponseInterface;

require_once dirname(__FILE__) . '/../../vendor/autoload.php';

class Api
{
    protected $client;

    /**
     * @var ResponseInterface $lastResponse
     */

    protected $lastResponse;

    /**
     * Construct a new API instance. This object fires off API requests and retrieves a JSON response
     *
     * @param $organisation string as it appears in your enverido account
     * @param $key string API key as it appears in your enverido account
     * @see https://docs.cogative.com/display/ENVD/API
     */

    public function __construct($organisation, $key)
    {
        $this->client = new Client([
            'base_uri' => 'https://'.$organisation.'.enverido.com',
            'headers' => [
                'X-API-KEY' => $key
            ]
        ]);
    }

    /**
     * Generate a random token to verify that the response is from the real enverido server. This token
     * is sent with some requests. The enverido server signs this token with the product's private key,
     * and returns a signed version. We can check this signature is genuine using the product's public key.
     *
     * @return string Generated token
     */

    public function generateToken() {
        return uniqid();
    }

    /**
     * Make a GET API request
     * @param $uri String endpoint to request (eg: /product/1)
     * @param $params array Array of form parameters
     *
     * @return string JSON response from API
     */

    public function get($uri, $params) {
        $request = $this->client->request('GET', $uri, [
            'query' => $params
        ]);

        $this->lastResponse = $request;

        return json_decode($request->getBody());
    }

    public function getLastResponse() {
        return $this->lastResponse;
    }

    public function getClient() {
        return $this->client;
    }
}
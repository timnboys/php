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
    /**
     * @var Client $client
     */
    protected $client;
    protected $organisation;

    private $key;

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
        // Set the API key
        $this->key = $key;

        // Set the organisation
        $this->organisation = $organisation;

        // Setup the HTTP client
        $this->initialiseClient();
    }

    protected function initialiseClient() {
        $this->client = new Client([
            'base_uri' => 'https://'.$this->organisation.'.enverido.com',
            'headers' => [
                'X-API-KEY' => $this->key
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
     * Make a GET API request to the API
     *
     * @param $uri String endpoint to request (eg: /product/1)
     * @param $params array Array of query parameters
     *
     * @return \stdClass JSON response from API
     */

    public function get($uri, $params) {
        $request = $this->client->request('GET', $uri, [
            'query' => $params
        ]);

        $this->lastResponse = $request;

        return json_decode($request->getBody());
    }

    /**
     * Send a POST request to the API
     *
     * @param string $uri URI to request
     * @param array $params Array of form parameters (key => value)
     * @return \stdClass JSON response from API
     */

    public function post($uri, $params) {
        $request = $this->client->request('POST', $uri, [
            'form_params' => $params
        ]);

        $this->lastResponse = $request;

        return json_decode($request->getBody());
    }

    /**
     * Send a PATCH request to the API
     *
     * @param string $uri URI to request
     * @param array $params Array of form parameters (key => value)
     * @return \stdClass JSON response from API
     */

    public function patch($uri, $params) {
        $request = $this->client->request('PATCH', $uri, [
            'form_params' => $params
        ]);

        $this->lastResponse = $request;

        return json_decode($request->getBody());
    }
    
    /**
     * Send a DELETE request to the API
     *
     * @param string $uri URI to call
     * @return \stdClass JSON response from API
     */

    public function delete($uri) {
        $request = $this->client->request('DELETE', $uri);

        $this->lastResponse = $request;
        return json_decode($request->getBody());
    }

    public function getLastResponse() {
        return $this->lastResponse;
    }

    public function getClient() {
        return $this->client;
    }

    /**
     * Return whether or not we're passing an API key with these requests.
     * @return boolean Whether or not API key is present
     */

    public function authenticated() {
        return $this->key != null;
    }

    /**
     * Set the API key to pass with requests. This method can be used to change the enverido account
     * you're authenticating as, or to add an API key to this instance so that protected API endpoints can
     * be called.
     *
     * @param string $organisation Organisation to query (find it in your enverido account)
     * @param string $key API key
     */

    public function authenticate($organisation, $key) {
       $this->organisation = $organisation;
        $this->key = $key;

        // reinitialise client using the new values
        $this->initialiseClient();
    }
}

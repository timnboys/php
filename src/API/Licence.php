<?php

/**
 * Copyright (c) 2016. Cogative LTD
 */

namespace Enverido\API;

use phpseclib\Crypt\RSA;

class Licence implements ApiResource
{
    protected $product;
    protected $id;
    protected $api;

    /**
     * Licence object from the API
     *
     * @param int $id
     * @param Api $api
     * @param Product $product
     */

    public function __construct($id, Api $api, Product $product) {
        $this->id = $id;
        $this->api = $api;
        $this->product = $product;
    }

    /**
     * Verify the licence using the details given. These should be as accurate as possible and not
     * provided by the user (with the exception of the email address)
     *
     * @param string $email Licence holder email address
     * @param string $ip Licensed IP address - only required if the product ties licences to IPs
     * @param string $domain Licensed domain name - only required if the product ties licences to domains
     * @param string $token A random token to prevent spoofing
     * @param string $publicKey Product's public key used to verify responses from enverido
     *
     * @return bool Whether or not licence is valid
     */

    public function verify($email, $ip=null, $domain=null, $token, $publicKey) {

        // phpseclib to verify signatures
        $rsa = new RSA();
        $rsa->loadKey($publicKey);

        // URI to query the API wtih
        $uri = "/product/".$this->product->getId()."/licence/".$this->getId()."/verify";

        // Get a response from the API

        /**
         * @var \stdClass $response
         */
        $response = $this->api->get($uri, [
            'email' => $email,
            'ip' => $ip,
            'domain' => $domain,
            'token' => $token
        ]);

        /* Verify the server's response */

        // Check that the token sent and signed token receive match. If they don't that means the response
        // isn't likely from the enverido server. Abort!

        // Signatures are base64 encoded so we need to decode as well
        $correctToken = $rsa->verify($token, base64_decode($response->signed_token));

        // Check licence signature matches
        $signatureContent = $email."|";

        // If both IP and domain aren't null then add both to signature content
        if($ip != null && $domain != null) {
            $signatureContent .= $ip."|".$domain;
        }
        // if IP is not null then this product probably ties licences to IP addresses
        if($ip != null) {
            $signatureContent .= $ip;
        }
        // If domain name is not null then this product probably ties licences to domain names
        else if($domain != null) {
            $signatureContent .= $domain;
        }

        // Check signature is valid
        $licenceSignatureValid = $rsa->verify($signatureContent, base64_decode($response->signature));

        return $response->valid && $licenceSignatureValid && $correctToken;

    }

    public function getId()
    {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

    /**
     * Update the resource at the enverido licence server. This requires a valid API
     * key to have been set. The properties set in the instance of the object are used
     * as the values to send to the licence server.
     *
     * @return boolean Returns true if update was successful
     */

    public function update()
    {
        // TODO: Implement update() method.
        return false;
    }

    /**
     * Activate the licence. Does not require an API key to do so. This will set the licence's issued
     * property to be true.
     *
     * @param string $email Email address of the licence holder. Required for activation
     * @return bool Whether or not the licence has been activated
     */

    public function activate($email) {
        $response = $this->api->post('/activate', [
            'licence' => $this->getId(),
            'email' => $email
        ]);

        if($response->issued) {
            return true;
        } else {
            return false;
        }
    }
}
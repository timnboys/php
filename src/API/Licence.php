<?php

/**
 * Copyright (c) 2016. Cogative LTD
 */

namespace Enverido\API;

use phpseclib\Crypt\RSA;

class Licence implements ApiResource
{

    /**
     * Licence object from the API
     *
     * @param Api $api
     * @param Product $product
     */

    protected $product;
    protected $id;
    protected $api;

    public function __construct(Api $api, Product $product) {
        $this->api = $api;
        $this->product = $product;
    }

    /**
     * Verify the licence using the details given. These should be as accurate as possible and not
     * provided by the user (with the exception of the email address)
     *
     * @param $email string Licence holder email address
     * @param null $ip string Licensed IP address - only required if the product ties licences to IPs
     * @param null $domain string Licensed domain name - only required if the product ties licences to domains
     * @param $token string A random token to prevent spoofing
     * @param $publicKey Product's public key used to verify responses from enverido
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
}
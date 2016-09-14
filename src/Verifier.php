<?php

/**
 * Copyright (c) 2016. Cogative LTD
 */

namespace Enverido;

use Enverido\API\Api;
use Enverido\API\Licence;
use Enverido\API\Product;

class Verifier
{
    protected $apiKey;
    protected $organisation;

    protected $api;

    /**
     * Create a new verifier object
     *
     * @param string $organisation Organisation as listed under your enverido account
     * @param string $apiKey API key as listed under your enverido account
     */

    public function __construct($organisation, $apiKey)
    {
        $this->apiKey = $apiKey;
        $this->organisation = $organisation;

        $this->api = new Api($organisation, $apiKey);
    }

    /**
     * Checks that a licence is valid for the provided details. This method checks the expiry date, whether
     * or not the licensed IP/domain matches the IP/domain given, and whether or not the licence has been
     * suspended. If the licence has been suspended, this method will return false.
     *
     * Also checks whether the response is from the genuine enverido server by using public/private key
     * signatures and a random token
     *
     * @param int $licenceId
     * @param int $productId
     * @param string $email
     * @param string $ip
     * @param string $domain
     * @param string $publicKey
     * @return bool Whether or not the licence is valid
     */

    public function verifyLicence($licenceId, $productId, $email, $ip=null, $domain=null, $publicKey) {
        $product = new Product($productId, $this->api);
        $licence = new Licence($licenceId, $this->api, $product);

        return $licence->verify($email, isset($ip) ? $ip : null, isset($domain) ? $domain : null,
            $this->api->generateToken(), $publicKey);
    }
}
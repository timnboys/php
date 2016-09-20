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
     */

    public function __construct($organisation)
    {
        $this->organisation = $organisation;
        $this->api = new Api($organisation, null);
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

    public function verifyLicenceViaId($licenceId, $productId, $email, $ip=null, $domain=null, $publicKey) {
        $product = new Product($productId, $this->api);
        $licence = new Licence($licenceId, $this->api, $product);

        return $licence->verify($email, isset($ip) ? $ip : null, isset($domain) ? $domain : null,
            $this->api->generateToken(), $publicKey);
    }

    /**
     * Check that a licence is valid for the provided domain name / ip address using its shortcode. This method
     * will ensure that the licence hasn't expired, been suspended and that the response comes from a genuine
     * enverido licence server.
     *
     * @param string $shortcode
     * @param string $email
     * @param string null $ip
     * @param string null $domain
     * @param string $publicKey
     *
     * @return bool Whether or not the licence is valid
     */

    public function verifyLicenceViaShortcode($shortcode, $email, $ip=null, $domain=null, $publicKey) {
        // Lookup the licence's information using its shortcode
        $lookup = new Lookup($this->organisation);
        /**
         * @var \stdClass $info
         */
        $info = $lookup->lookup($shortcode);

        $licence = new Licence($info->licence_id, $this->api, new Product($info->product_id, $this->api));

        return $licence->verify($email, isset($ip) ? $ip : null, isset($domain) ? $domain : null,
            $this->api->generateToken(), $publicKey);
    }
}
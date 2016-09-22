<?php

/**
 * Copyright (c) 2016. Cogative LTD
 */

namespace Enverido\API;

use phpseclib\Crypt\RSA;

class Licence implements ApiResource
{
    protected $id;
    protected $api;
    protected $product;

    protected $shortCode;
    protected $email;
    protected $domain;
    protected $ip;

    protected $expiryTimestamp;

    protected $suspended;
    protected $issued;

    protected $signature;
    protected $expirySignature;

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

        // Get licence info as long as the API key is present
        if($this->api->authenticated() && $this->id != null) {
            /**
             * @var \stdClass $info
             */
            $info = $this->api->get('/product/'.$product->getId().'/licence/'.$this->getId(), null);

            $this->email = $info->email;
            $this->shortCode = $info->short_code;
            $this->signature = $info->signature;
            $this->expirySignature = $info->expiry_signature;
            $this->expiryTimestamp = $info->expiry_timestamp;
            $this->suspended = $info->suspended;
            $this->issued = $info->issued;

            $this->ip = $info->ip;
            $this->domain = $info->domain;
        }
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
     * Delete this licence. This is an irreversible change.
     * Here be dragons!
     *
     * Requires an API key to be set.
     *
     * @return bool
     */

    public function delete() {
        // If no API key is set this is going to fail anyway, so we might as well get it
        // over with now!
        if(!$this->api->authenticated()) {
            return false;
        }

        $response = $this->api->delete('/product/'.$this->product->getId().'/licence/'.$this->getId());
        if(property_exists($response, 'deleted')) {
            return $response->deleted;
        } else {
            return false;
        }
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

    /**
     * @return Product
     */
    public function getProduct()
    {
        return $this->product;
    }

    /**
     * @param Product $product
     */
    public function setProduct($product)
    {
        $this->product = $product;
    }

    /**
     * @return mixed
     */
    public function getShortCode()
    {
        return $this->shortCode;
    }

    /**
     * @param mixed $shortCode
     */
    public function setShortCode($shortCode)
    {
        $this->shortCode = $shortCode;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @return mixed
     */
    public function getDomain()
    {
        return $this->domain;
    }

    /**
     * @param mixed $domain
     */
    public function setDomain($domain)
    {
        $this->domain = $domain;
    }

    /**
     * @return mixed
     */
    public function getIp()
    {
        return $this->ip;
    }

    /**
     * @param mixed $ip
     */
    public function setIp($ip)
    {
        $this->ip = $ip;
    }

    /**
     * @return mixed
     */
    public function getExpiryTimestamp()
    {
        return $this->expiryTimestamp;
    }

    /**
     * @param mixed $expiryTimestamp
     */
    public function setExpiryTimestamp($expiryTimestamp)
    {
        $this->expiryTimestamp = $expiryTimestamp;
    }

    /**
     * @return mixed
     */
    public function getSuspended()
    {
        return $this->suspended;
    }

    /**
     * @param mixed $suspended
     */
    public function setSuspended($suspended)
    {
        $this->suspended = $suspended;
    }

    /**
     * @return mixed
     */
    public function getIssued()
    {
        return $this->issued;
    }

    /**
     * @param mixed $issued
     */
    public function setIssued($issued)
    {
        $this->issued = $issued;
    }

    /**
     * @return mixed
     */
    public function getSignature()
    {
        return $this->signature;
    }

    /**
     * @return mixed
     */
    public function getExpirySignature()
    {
        return $this->expirySignature;
    }

    /**
     * Create a licence using the information provided on this object.
     *
     * @param IssuingAuthority $authority The issuing authority used to generate the licence
     * @return \stdClass
     *
     * @see https://docs.cogative.com/pages/viewpage.action?pageId=1409441#id-/licence-POST
     */

    public function create(IssuingAuthority $authority) {
        $request = $this->api->post('/product/'.$this->getProduct()->getId().'/licence', [
            'email' => $this->getEmail(),
            'expiry' => $this->getExpiryTimestamp(),
            'domain' => $this->getDomain(),
            'ip' => $this->getIp(),
            'authority' => $authority->getId()
        ]);

        $this->setShortCode($request->short_code);
        $this->setId($request->id);

        // Will return the response from the licence server, this will include info like the licence ID,
        // licence signature, licence expiry signature
        return $request;
    }

}
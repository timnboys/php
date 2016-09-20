<?php

/**
 * Copyright (c) 2016. Cogative LTD
 */

namespace Enverido\API;


class Product implements ApiResource
{
    protected $api;

    protected $id;
    protected $name;
    protected $code;
    protected $description;
    protected $lockIp;
    protected $lockDomain;

    /**
     * Product constructor.
     * @param int $id
     * @param Api $api
     */

    public function __construct($id, Api $api) {
        $this->id = $id;
        $this->api = $api;

        // If API key is set we can retrieve product details
        if($api->authenticated()) {

            /**
             * @var \stdClass $infoResponse Product information retrieved from the API.
             * @see https://docs.cogative.com/pages/viewpage.action?pageId=1409436
             */
            $infoResponse = $this->api->get('/product/' . $this->id, null);

            // Set properties
            $this->name = $infoResponse->name;
            $this->code = $infoResponse->code;
            $this->description = $infoResponse->description;
            $this->lockIp = $infoResponse->lock_ip;
            $this->lockDomain = $infoResponse->lock_domain_name;
        }
    }

    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function index() {
        return $this->api->get('/product', null);
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
        // It's not currently possible to update product details via the API.
        return false;
    }

    /**
     * Get the product's name
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Get the product's code
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Get the product's description
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Get whether or not the product locks licences to IPs
     * @return boolean
     */
    public function getLockIp()
    {
        return $this->lockIp;
    }

    /**
     * Get whether or not the product locks licences to domains
     * @return boolean
     */
    public function getLockDomain()
    {
        return $this->lockDomain;
    }
}
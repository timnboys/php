<?php

/**
 * Copyright (c) 2016. Cogative LTD
 */

namespace Enverido\API;


class IssuingAuthority implements ApiResource
{
    protected $api;
    protected $id;


    /**
     * Product constructor.
     * @param int $id
     * @param Api $api
     */

    public function __construct($id, Api $api) {
        $this->id = $id;
        $this->api = $api;
    }

    public function getId() {
        return $this->id;
    }

    /**
     * Set the resource ID
     * @return void
     */
    public function setId($id)
    {
        // TODO: Implement setId() method.
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
    }
}
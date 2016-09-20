<?php

/**
 * Copyright (c) 2016. Cogative LTD
 */

namespace Enverido\API;


interface ApiResource
{
    /**
     * Returns the resource's ID
     * @return int
     */

    public function getId();

    /**
     * Set the resource ID
     * @return void
     */

    public function setId($id);

    /**
     * Update the resource at the enverido licence server. This requires a valid API
     * key to have been set. The properties set in the instance of the object are used
     * as the values to send to the licence server.
     *
     * @return boolean Returns true if update was successful
     */

    public function update();
}
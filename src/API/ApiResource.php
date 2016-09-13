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
}
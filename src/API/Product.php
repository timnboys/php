<?php

/**
 * Copyright (c) 2016. Cogative LTD
 */

namespace Enverido\API;


class Product implements ApiResource
{
    protected $id;
    protected $api;

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

    public function setId($id) {
        $this->id = $id;
    }

    public function index() {
        return $this->api->get('/product', null);
    }
}
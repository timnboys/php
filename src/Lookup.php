<?php

/**
 * Copyright (c) 2016. Cogative LTD
 */

namespace Enverido;


use Enverido\API\Api;

class Lookup
{
    protected $organisation;
    protected $api;

    /**
     * Lookup constructor.
     *
     * @param string $organisation The organisation you're looking up licences for
     */

    public function __construct($organisation)
    {
        $this->organisation = $organisation;

        // No API key required since we're not going to be calling any endpoints that require authentication
        $this->api = new Api($this->organisation, null);
    }

    /**
     * Lookup a licence's information using its shortcode. The licence must belong to the organisation you
     * specified when you instantiated the Lookup class. This will return an object containing the licence ID,
     * product ID and licence shortcode.
     *
     * @param string $shortcode Licence shortcode
     * @return \stdClass Object containing licence information
     * @see https://docs.cogative.com/pages/viewpage.action?pageId=1409624
     */

    public function lookup($shortcode) {
        $response = $this->api->get('/lookup', [
            'shortcode' => $shortcode
        ]);

        return $response;
    }
}
<?php

/**
 * Copyright (c) 2016. Cogative LTD
 */

use PHPUnit\Framework\TestCase;

class LookupTest extends TestCase
{
    /**
     * Test that a valid lookup will return correct information
     */

    public function testValidLookup() {
        // Load configuration from .env
        $config = new \Dotenv\Dotenv(__DIR__);
        $config->load();

        // New lookup instance
        $lookup = new \Enverido\Lookup(getenv('ORGANISATION'));
        $response = $lookup->lookup(getenv('LICENCE_SHORTCODE'));

        // Check that all the expected attributes are returned
        $this->assertObjectHasAttribute('licence_id', $response);
        $this->assertObjectHasAttribute('product_id', $response);
        $this->assertObjectHasAttribute('short_code', $response);
    }

    /**
     * Check that looking up an invalid licence shortcode will throw 404
     */

    public function testInvalidLookup() {
        // Load configuration from .env
        $config = new \Dotenv\Dotenv(__DIR__);
        $config->load();

        $this->expectException(\GuzzleHttp\Exception\ClientException::class);

        // New lookup instance
        $lookup = new \Enverido\Lookup(getenv('ORGANISATION'));
        $lookup->lookup('blabla-123');
    }
}

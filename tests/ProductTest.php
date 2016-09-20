<?php

/**
 * Copyright (c) 2016. Cogative LTD
 */

use PHPUnit\Framework\TestCase;

class ProductTest extends TestCase {

    /**
     * Test that the product index can be retrieved when authenticated with an API key
     */

    public function testIndex() {
        $config = new \Dotenv\Dotenv(__DIR__);
        $config->load();

        $api = new \Enverido\API\Api(getenv('ORGANISATION'), getenv('API_KEY'));
        $product = new \Enverido\API\Product(getenv('PRODUCT_ID'), $api);

        $this->assertObjectHasAttribute('id', $product->index()[0]);
    }

    /**
     * Test that the product index can't be retrieved without an API key
     */

    public function testForbiddenIndex() {
        $config = new \Dotenv\Dotenv(__DIR__);
        $config->load();

        // We're expecting a 403 response to trigger an exception
        $this->expectException(\GuzzleHttp\Exception\ClientException::class);

        $api = new \Enverido\API\Api(getenv('ORGANISATION'), null);
        $product = new \Enverido\API\Product(getenv('PRODUCT_ID'), $api);
        $product->index();
    }

    /**
     * Test that individual product properties can be retrieved when authenticated with an API key
     */

    public function testPropertiesSet() {
        $config = new \Dotenv\Dotenv(__DIR__);
        $config->load();

        $api = new \Enverido\API\Api(getenv('ORGANISATION'), getenv('API_KEY'));
        $product = new \Enverido\API\Product(getenv('PRODUCT_ID'), $api);

        $this->assertNotNull($product->getId());
        $this->assertNotNull($product->getCode());
        $this->assertNotNull($product->getDescription());
        $this->assertNotNull($product->getLockDomain());
        $this->assertNotNull($product->getLockIp());
        $this->assertNotNull($product->getName());
    }

    /**
     * Test that individual product properties aren't retreived when not authenticated
     */

    public function testPropertiesNotSet() {
        $config = new \Dotenv\Dotenv(__DIR__);
        $config->load();

        $api = new \Enverido\API\Api(getenv('ORGANISATION'), null);
        $product = new \Enverido\API\Product(getenv('PRODUCT_ID'), $api);

        $this->assertNotNull($product->getId());
        $this->assertNull($product->getCode());
        $this->assertNull($product->getDescription());
        $this->assertNull($product->getLockDomain());
        $this->assertNull($product->getLockIp());
        $this->assertNull($product->getName());
    }

}
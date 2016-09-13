<?php
/**
 * Copyright (c) 2016. Cogative LTD
 */

use PHPUnit\Framework\TestCase;

class ProductTest extends TestCase {

    public function testIndex() {
        $config = new \Dotenv\Dotenv(__DIR__);
        $config->load();

        $api = new \Enverido\API\Api(getenv('ORGANISATION'), getenv('API_KEY'));
        $product = new \Enverido\API\Product(getenv('PRODUCT_ID'), $api);

        $this->assertObjectHasAttribute('id', $product->index()[0]);
    }

}
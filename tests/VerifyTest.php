<?php

use PHPUnit\Framework\TestCase;

class VerifyTest extends TestCase {

    public function testVerify() {
        $config = new \Dotenv\Dotenv(__DIR__);
        $config->load();

        $api = new \Enverido\API\Api(getenv('ORGANISATION'), getenv('API_KEY'));

        $product = new \Enverido\API\Product(getenv('PRODUCT_ID'), $api);

        $licence = new \Enverido\API\Licence($api, $product);
        $licence->setId(getenv('LICENCE_ID'));

        $this->assertTrue($licence->verify(getenv('EMAIL'), getenv('IP'), null, 'blabla', getenv('PUBLIC_KEY')));
    }

}
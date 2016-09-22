<?php

use PHPUnit\Framework\TestCase;

class LicenceTest extends TestCase {

    public function testVerify() {
        $config = new \Dotenv\Dotenv(__DIR__);
        $config->load();

        $api = new \Enverido\API\Api(getenv('ORGANISATION'), getenv('API_KEY'));

        $product = new \Enverido\API\Product(getenv('PRODUCT_ID'), $api);

        $licence = new \Enverido\API\Licence(getenv('LICENCE_ID'), $api, $product);

        $this->assertTrue($licence->verify(getenv('EMAIL'), getenv('IP'), null, 'blabla', getenv('PUBLIC_KEY')));
    }

    /**
     * Test that a licence can be deleted
     */

    public function testCreateAndDelete() {
        $config = new \Dotenv\Dotenv(__DIR__);
        $config->load();

        $api = new \Enverido\API\Api(getenv('ORGANISATION'), getenv('API_KEY'));
        $product = new \Enverido\API\Product(getenv('PRODUCT_ID'), $api);
        $authority = new \Enverido\API\IssuingAuthority(1, $api);

        $licenceToDelete = new \Enverido\API\Licence(null, $api, $product);
        $licenceToDelete->setEmail(getenv('EMAIL'));
        $licenceToDelete->setIp(getenv('IP'));
        $licenceToDelete->setExpiryTimestamp(1577836800);

        $licenceToDelete->create($authority);
        // Check that licence was actually created
        $this->assertNotNull($licenceToDelete->getId());

        // Now delete it
        $this->assertTrue($licenceToDelete->delete());
    }
}
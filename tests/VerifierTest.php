<?php

/**
 * Copyright (c) 2016. Cogative LTD
 */

use PHPUnit\Framework\TestCase;

class VerifierTest extends TestCase
{
    /**
     * Test that licence verification passes with a known valid licence. All being well the
     * verifyLicence method will return true
     */

    public function testVerifyLicenceValid() {
        // Load configuration from .env
        $config = new \Dotenv\Dotenv(__DIR__);
        $config->load();

        // New verifier instance
        $verifier = new \Enverido\Verifier(getenv('ORGANISATION'));

        // Check if licence is verified using known valid licence details
        $verified = $verifier->verifyLicenceViaId(getenv('LICENCE_ID'), getenv('PRODUCT_ID'), getenv('EMAIL'), getenv('IP'),
            null, getenv('PUBLIC_KEY'));

        $this->assertTrue($verified);
    }

    /**
     * Test that licence a licence can be verified using only its shortcode
     */

    public function testVerifyLicenceViaShortcode() {
        // Load configuration from .env
        $config = new \Dotenv\Dotenv(__DIR__);
        $config->load();

        // New verifier instance
        $verifier = new \Enverido\Verifier(getenv('ORGANISATION'));

        // Check if licence is verified using known valid licence details
        $verified = $verifier->verifyLicenceViaShortcode(getenv('LICENCE_SHORTCODE'), getenv('EMAIL'), getenv('IP'),
            null, getenv('PUBLIC_KEY'));

        $this->assertTrue($verified);
    }

    /**
     * Test that licence verification fails with an invalid email address for a known valid licence
     */

    public function testVerifyLicenceInvalidEmail() {
        // Load configuration from .env
        $config = new \Dotenv\Dotenv(__DIR__);
        $config->load();

        // New verifier instance
        $verifier = new \Enverido\Verifier(getenv('ORGANISATION'));

        // Check if licence is verified using known valid licence details
        $verified = $verifier->verifyLicenceViaId(getenv('LICENCE_ID'), getenv('PRODUCT_ID'), 'yaya@ya.com', getenv('IP'),
            null, getenv('PUBLIC_KEY'));

        $this->assertNotTrue($verified);
    }

    /**
     * Test that licence verification fails with an invalid IP address for a known valid licence
     */

    public function testVerifyLicenceInvalidIP() {
        // Load configuration from .env
        $config = new \Dotenv\Dotenv(__DIR__);
        $config->load();

        // New verifier instance
        $verifier = new \Enverido\Verifier(getenv('ORGANISATION'));

        // Check if licence is verified using known valid licence details
        $verified = $verifier->verifyLicenceViaId(getenv('LICENCE_ID'), getenv('PRODUCT_ID'), getenv('EMAIL'), '10.0.0.1',
            null, getenv('PUBLIC_KEY'));

        $this->assertNotTrue($verified);
    }

    /**
     * Test that licence verification fails with an invalid public key for a known valid licence
     */

    public function testVerifyLicenceInvalidPublicKey() {
        // Load configuration from .env
        $config = new \Dotenv\Dotenv(__DIR__);
        $config->load();

        // New verifier instance
        $verifier = new \Enverido\Verifier(getenv('ORGANISATION'));

        // Check if licence is verified using known valid licence details
        $verified = $verifier->verifyLicenceViaId(getenv('LICENCE_ID'), getenv('PRODUCT_ID'), getenv('EMAIL'), getenv('IP'),
            null, 'publickey');

        $this->assertNotTrue($verified);
    }

    /**
     * Test that licence verification fails with an invalid public key for a known valid licence
     */

    public function testVerifyLicenceInvalidLicenceId() {
        // Load configuration from .env
        $config = new \Dotenv\Dotenv(__DIR__);
        $config->load();

        // This should throw a 404 error wrapped in a ClientException, since the licence shouldn't exist
        $this->expectException(\GuzzleHttp\Exception\ClientException::class);

        // New verifier instance
        $verifier = new \Enverido\Verifier(getenv('ORGANISATION'));

        // Check if licence is verified using known valid licence details
        $verified = $verifier->verifyLicenceViaId(44, getenv('PRODUCT_ID'), getenv('EMAIL'), getenv('IP'),
            null, getenv('PUBLIC_KEY'));

        $this->assertNotTrue($verified);
    }
}

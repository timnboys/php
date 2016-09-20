<?php

/**
 * Copyright (c) 2016. Cogative LTD
 */

use PHPUnit\Framework\TestCase;

class ActivatorTest extends TestCase
{
    /**
     * Test that a licence can be activated via short code. This test will only pass if the licence hasn't been
     * issued. You might want to reset this in the enverido dashboard before commencing the test.
     */

    public function testActivateViaShortcode() {
        // Load configuration from .env
        $config = new \Dotenv\Dotenv(__DIR__);
        $config->load();

        // New activator instance
        $activator = new \Enverido\Activator(getenv('ORGANISATION'));
        $activation = $activator->activateViaShortcode(getenv('LICENCE_SHORTCODE'), getenv('EMAIL'));

        $this->assertTrue($activation);
    }
}

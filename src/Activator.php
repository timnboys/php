<?php

/**
 * Copyright (c) 2016. Cogative LTD
 */

namespace Enverido;


use Enverido\API\Api;
use Enverido\API\Licence;
use Enverido\API\Product;
use GuzzleHttp\Exception\ClientException;

class Activator
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
     * Activate the product. We pass the email address associated with the licence to prove our identity. This method
     * will only activate a licence that hasn't been issued.
     *
     * @param string $shortcode Licence shortcode
     * @param string $email Licence holder email address
     *
     * @return bool True if licence issued successfully, product can be activated.
     */

    public function activateViaShortcode($shortcode, $email) {
        $lookup = new Lookup($this->organisation);

        /**
         * @var \stdClass $licenceInfo Licence information
         */
        $licenceInfo = $lookup->lookup($shortcode);

        $licence = new Licence($licenceInfo->licence_id, $this->api, new Product($licenceInfo->product_id, $this->api));

        try {
            $licence->activate($email);
        } catch(ClientException $ce) {
            // Almost certainly the case that the licence has already been activated
            if($ce->getCode() == 409) {
                return false;
            }

            // At some point we should add an enum type class to differentiate the responses here.
            return false;
        }

        // if we get this far the licence has been issued, and therefore the product activated
        return true;
    }

    /**
     * Activate the product. We pass the email address associated with the licence to prove our identity. This method
     * will only activate a licence that hasn't been issued.
     *
     * @param int $licenceId Licence ID
     * @param int $productId Product ID
     * @param string $email Licence holder email address
     *
     * @return bool True if licence issued successfully, product can be activated.
     */

    public function activateViaId($licenceId, $productId, $email) {
        $licence = new Licence($licenceId, $this->api, new Product($productId, $this->api));

        try {
            $licence->activate($email);
        } catch(ClientException $ce) {
            // Almost certainly the case that the licence has already been activated
            if($ce->getCode() == 409) {
                return false;
            }

            // At some point we should add an enum type class to differentiate the responses here.
            return false;
        }

        // if we get this far the licence has been issued, and therefore the product activated
        return true;
    }
}
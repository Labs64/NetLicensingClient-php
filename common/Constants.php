<?php
/**
 * @author    Labs64 <netlicensing@labs64.com>
 * @license   Apache-2.0
 * @link      http://netlicensing.io
 * @copyright 2017 Labs64 NetLicensing
 */

namespace NetLicensing;


class Constants
{
    /**
     * Security modes
     */
    const BASIC_AUTHENTICATION = 'BASIC_AUTH';
    const APIKEY_IDENTIFICATION = 'APIKEY';

    const XML_NS = 'http://netlicensing.labs64.com/schema/context';

    /**
     * Licensing Models
     */
    const LICENSING_MODEL_TRY_AND_BUY = "TryAndBuy";
    const LICENSING_MODEL_RENTAL = "Rental";
    const LICENSING_MODEL_SUBSCRIPTION = "Subscription";
    const LICENSING_MODEL_FLOATING = "Floating";
    const LICENSING_MODEL_MULTI_FEATURE = "MultiFeature";
    const LICENSING_MODEL_MULTI_PAY_PER_USE = "PayPerUse";
    const LICENSING_MODEL_PRICING_TABLE = "PricingTable";
    const LICENSING_MODEL_QUOTA = "Quota";

    /**
     * Licensee
     */
    const LICENSEE_ENDPOINT_PATH = 'licensee';
    const LICENSEE_ENDPOINT_PATH_VALIDATE = 'validate';
    const LICENSEE_ENDPOINT_PATH_TRANSFER = 'transfer';

    /**
     * License
     */
    const LICENSE_ENDPOINT_PATH = 'license';

    /*
     * License Template
     */
    const LICENSE_TEMPLATE_ENDPOINT_PATH = 'licensetemplate';

    /**
     * Payment Method
     */
    const PAYMENT_METHOD_ENDPOINT_PATH = 'paymentmethod';

    /**
     * Product Module
     */
    const PRODUCT_MODULE_ENDPOINT_PATH = 'productmodule';

    /*
     * Product
     */
    const PRODUCT_ENDPOINT_PATH = 'product';

    /*
    * Token
    */
    const TOKEN_ENDPOINT_PATH = 'token';

    /*
    * Transaction
    */
    const TRANSACTION_ENDPOINT_PATH = 'transaction';

    /**
     * Utility
     */
    const UTILITY_ENDPOINT_PATH = 'utility';
}





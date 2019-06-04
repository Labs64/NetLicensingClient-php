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
    const NETLICENSING_VERSION = '2.5.0';

    const NUMBER = 'number';
    const CASCADE = 'forceCascade';
    const FILTER = 'filter';

    /**
     * Security modes
     */
    const BASIC_AUTHENTICATION = 'BASIC_AUTH';
    const APIKEY_IDENTIFICATION = 'APIKEY';
    const ANONYMOUS_IDENTIFICATION = 'ANONYMOUS';

    /**
     * @deprecated
     */
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
    const LICENSING_MODEL_NODE_LOCKED = "NodeLocked";

    /**
     * Licensee
     */
    const LICENSEE_ENDPOINT_PATH = 'licensee';
    const LICENSEE_ENDPOINT_PATH_VALIDATE = 'validate';
    const LICENSEE_ENDPOINT_PATH_TRANSFER = 'transfer';
    const LICENSEE_NUMBER = 'licenseeNumber';
    const LICENSEE_PROP_LICENSEE_NAME = 'licenseeName';
    /**
     * @deprecated please use License::LICENSE_PROP_LICENSEE_SECRET instead.
     */
    const LICENSEE_PROP_LICENSEE_SECRET = 'licenseeSecret';
    const LICENSEE_SOURCE_LICENSEE_NUMBER = 'sourceLicenseeNumber';

    /**
     * License
     */
    const LICENSE_ENDPOINT_PATH = 'license';
    const LICENSE_NUMBER = 'licenseNumber';
    const LICENSE_PROP_LICENSEE_SECRET = 'licenseeSecret';

    /*
     * License Template
     */
    const LICENSE_TEMPLATE_ENDPOINT_PATH = 'licensetemplate';
    const LICENSE_TEMPLATE_NUMBER = 'licenseTemplateNumber';
    const LICENSE_TEMPLATE_PROP_LICENSEE_SECRET = 'licenseeSecret';

    /**
     * Payment Method
     */
    const PAYMENT_METHOD_ENDPOINT_PATH = 'paymentmethod';

    /**
     * Product Module
     */
    const PRODUCT_MODULE_ENDPOINT_PATH = 'productmodule';
    const PRODUCT_MODULE_NUMBER = 'productModuleNumber';
    const PRODUCT_MODULE_PROP_LICENSEE_SECRET_MODE = 'licenseeSecretMode';

    /*
     * Product
     */
    const PRODUCT_ENDPOINT_PATH = 'product';
    const PRODUCT_NUMBER = 'productNumber';

    /*
    * Token
    */
    const TOKEN_ENDPOINT_PATH = 'token';
    const TOKEN_EXPIRATION_TIME = 'expirationTime';

    /*
    * Transaction
    */
    const TRANSACTION_ENDPOINT_PATH = 'transaction';
    const TRANSACTION_NUMBER = 'transactionNumber';
    const TRANSACTION_DATE_CREATED = "datecreated";
    const TRANSACTION_DATE_CLOSED = "dateclosed";

    /**
     * Utility
     */
    const UTILITY_ENDPOINT_PATH = 'utility';
    const UTILITY_ENDPOINT_PATH_LICENSE_TYPES = 'licenseTypes';
    const UTILITY_ENDPOINT_PATH_LICENSING_MODELS = 'licensingModels';
    const UTILITY_ENDPOINT_PATH_COUNTRIES = 'countries';

    /**
     * Vendor
     */
    const VENDOR_NUMBER = 'vendorNumber';
}





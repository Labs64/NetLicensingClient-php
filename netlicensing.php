<?php
/**
 * @author    Labs64 <netlicensing@labs64.com>
 * @license   Apache-2.0
 * @link      http://netlicensing.io
 * @copyright 2017 Labs64 NetLicensing
 */

require_once(__DIR__ . '/common/Constants.php');

require_once(__DIR__ . '/vo/Context.php');
require_once(__DIR__ . '/vo/NetLicensingCurl.php');
require_once(__DIR__ . '/vo/ValidationParameters.php');
require_once(__DIR__ . '/vo/ValidationResults.php');

require_once(__DIR__ . '/exception/MalformedArgumentsException.php');
require_once(__DIR__ . '/exception/NetLicensingException.php');
require_once(__DIR__ . '/exception/RestException.php');

require_once(__DIR__ . '/util/CheckUtils.php');

require_once(__DIR__ . '/entity/traits/Properties.php');
require_once(__DIR__ . '/entity/BaseEntity.php');
require_once(__DIR__ . '/entity/Product.php');
require_once(__DIR__ . '/entity/ProductDiscount.php');
require_once(__DIR__ . '/entity/ProductModule.php');
require_once(__DIR__ . '/entity/LicenseTemplate.php');
require_once(__DIR__ . '/entity/Licensee.php');
require_once(__DIR__ . '/entity/License.php');
require_once(__DIR__ . '/entity/Transaction.php');
require_once(__DIR__ . '/entity/Token.php');
require_once(__DIR__ . '/entity/PaymentMethod.php');

require_once(__DIR__ . '/service/NetLicensingService.php');
require_once(__DIR__ . '/service/ProductService.php');
require_once(__DIR__ . '/service/ProductModuleService.php');
require_once(__DIR__ . '/service/LicenseTemplateService.php');
require_once(__DIR__ . '/service/UtilityService.php');
require_once(__DIR__ . '/service/LicenseeService.php');
require_once(__DIR__ . '/service/LicenseService.php');
require_once(__DIR__ . '/service/TransactionService.php');
require_once(__DIR__ . '/service/TokenService.php');
require_once(__DIR__ . '/service/PaymentMethodService.php');
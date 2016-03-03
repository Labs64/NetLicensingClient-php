<?php
/**
 * @author    Labs64 <netlicensing@labs64.com>
 * @license   Apache-2.0
 * @link      http://netlicensing.io
 * @copyright 2016 Labs64 NetLicensing
 */

require_once(__DIR__ . '/Exceptions/NetLicensingException.php');
require_once(__DIR__ . '/RestController/NetLicensingAPI.php');

// Entities
require_once(__DIR__ . '/Entities/BaseEntity.php');
require_once(__DIR__ . '/Entities/Product.php');
require_once(__DIR__ . '/Entities/ProductModule.php');
require_once(__DIR__ . '/Entities/Licensee.php');
require_once(__DIR__ . '/Entities/LicenseTemplate.php');
require_once(__DIR__ . '/Entities/Token.php');

// Services
require_once(__DIR__ . '/Services/BaseEntityService.php');
require_once(__DIR__ . '/Services/ProductService.php');
require_once(__DIR__ . '/Services/ProductModuleService.php');
require_once(__DIR__ . '/Services/LicenseeService.php');
require_once(__DIR__ . '/Services/LicenseTemplateService.php');
require_once(__DIR__ . '/Services/TokenService.php');

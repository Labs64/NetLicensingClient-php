<?php

require_once(__DIR__ . '/Exceptions/NetLicensingException.php');
require_once(__DIR__ . '/RestController/NetLicensingAPI.php');

//entities
require_once(__DIR__ . '/Entities/BaseEntity.php');
require_once(__DIR__ . '/Entities/Product.php');
require_once(__DIR__ . '/Entities/ProductModule.php');
require_once(__DIR__ . '/Entities/Licensee.php');
require_once(__DIR__ . '/Entities/Token.php');

//Services
require_once(__DIR__ . '/Services/BaseEntityService.php');
require_once(__DIR__ . '/Services/ProductService.php');
require_once(__DIR__ . '/Services/ProductModuleService.php');
require_once(__DIR__ . '/Services/LicenseeService.php');
require_once(__DIR__ . '/Services/TokenService.php');
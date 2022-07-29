<?php

include './vendor/autoload.php';

use NetLicensing\ValidationService;
use NetLicensing\Context;
use NetLicensing\ValidationParameters;

$context = new Context();
$context->setBaseUrl('http://localhost:28080/core/v2/rest');
$context->setUsername('demo');
$context->setPassword('demo');

$validationParameters = new ValidationParameters();
$validationParameters->setProductNumber('PEXTIJYMV');
$validationParameters->setLicenseeName('Slava');
$validationParameters->setLicenseeProperty('test', 123123);

$meta = [];

$validationResult = ValidationService::validate($context, '8888', $validationParameters, $meta);

//print_r($validationResult);
//print_r($meta);
<?php

require_once(__DIR__ . '/vendor/autoload.php');
require_once(__DIR__ . '/netlicensing.php');


$context = new \NetLicensing\Context();
$context->setUsername('demo');
$context->setPassword('demo');
$context->setBaseUrl('http://localhost:28080/core/v2/rest');


$token = new \NetLicensing\Token();
$token->setExpirationTime(new DateTime());

$newToken = \NetLicensing\TokenService::create($context, $token);



print_r($newToken->getExpirationTime()->setTimezone(new DateTimeZone('Europe/Minsk')));

//$product = new \NetLicensing\Product();
//$product->setName('test');
//$product->setVersion('1.0');
//$product->setNumber('test');
//
//$products = \NetLicensing\ProductService::getList($context);
//
//foreach ($products as $product) {
//    print_r($product->asPropertiesMap());
//}




//$json = file_get_contents(__DIR__.'/json.json');
//
//$response = json_decode($json);
//
//print_r(\NetLicensing\ItemsToArrayConverter::convert($response->items->item));
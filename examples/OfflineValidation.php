<?php

use PHPUnit\Framework\TestCase;
use NetLicensing\Context;
use NetLicensing\ValidationService;

class OfflineValidation extends TestCase
{
    public function testOfflineValidation()
    {
        try {
            // 1. Create context, for offline validation you only need to provide the public key.
            $publicKey = file_get_contents(__DIR__ . '../resources/rsa_public.pem');
            $context = new Context();
            $context->setPublicKey($publicKey);

            // 2. Read the validation file.
            $offlineValidation = file_get_contents(__DIR__ . '../resources/Isb-DEMO.xml');

            // 3. Validate. ValidationResult is same as if validation would be executed against the
            // NetLicensing service online.
            $meta = [];
            $validationResult = ValidationService::validateOffline($context, $offlineValidation, $meta);
            $this->assertNotEmpty($validationResult);
        } catch (Exception $e) {
            $this->fail($e->getMessage());
        }
    }
}

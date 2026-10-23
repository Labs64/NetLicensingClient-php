<?php

use NetLicensing\BadSignatureException;
use NetLicensing\Context;
use NetLicensing\MalformedArgumentsException;
use NetLicensing\ValidationService;
use PHPUnit\Framework\TestCase;

class OfflineValidationTest extends TestCase
{
    private const RESOURCES = __DIR__ . '/../examples/resources/';

    private function context(?string $publicKeyFile = 'rsa_public.pem'): Context
    {
        $context = new Context();

        if ($publicKeyFile) {
            $context->setPublicKey(file_get_contents(self::RESOURCES . $publicKeyFile));
        }

        return $context;
    }

    private function validationFile(): string
    {
        return file_get_contents(self::RESOURCES . 'Isb-DEMO.xml');
    }

    public function testValidSignatureReturnsValidationResults()
    {
        $meta = [];
        $result = ValidationService::validateOffline($this->context(), $this->validationFile(), $meta);

        $validation = $result->getProductModuleValidation('Msb-DEMO');
        $this->assertSame('true', $validation['valid']);
        $this->assertSame('Subscription', $validation['licensingModel']);
        $this->assertSame('2021-04-21T20:15:45.694Z', $validation['expires']);
        $this->assertEquals(new DateTime('2020-12-28T22:53:06.681Z'), $result->getTtl());
        $this->assertSame([], $meta);
    }

    public function testWrongPublicKeyIsRejected()
    {
        $this->expectException(BadSignatureException::class);
        ValidationService::validateOffline($this->context('rsa_public_wrong.pem'), $this->validationFile());
    }

    public function testTamperedContentIsRejected()
    {
        $tampered = str_replace('"valid">true', '"valid">false', $this->validationFile());

        $this->expectException(BadSignatureException::class);
        ValidationService::validateOffline($this->context(), $tampered);
    }

    public function testTamperedSignatureValueIsRejected()
    {
        $tampered = preg_replace('#<SignatureValue>j#', '<SignatureValue>k', $this->validationFile());

        $this->expectException(BadSignatureException::class);
        ValidationService::validateOffline($this->context(), $tampered);
    }

    public function testMissingSignatureIsRejected()
    {
        $unsigned = preg_replace('#<Signature>.*</Signature>#s', '', $this->validationFile());

        $this->expectException(BadSignatureException::class);
        ValidationService::validateOffline($this->context(), $unsigned);
    }

    public function testCommentInjectionDoesNotTruncateValues()
    {
        // comments are excluded from the signed data (Reference URI=""), so they must not alter the parsed values
        $injected = str_replace('>Msb-DEMO<', '>Msb-<!---->DEMO<', $this->validationFile());

        $result = ValidationService::validateOffline($this->context(), $injected);

        $this->assertSame(['Msb-DEMO'], array_keys($result->getValidations()));
    }

    public function testMalformedXmlIsRejected()
    {
        $this->expectException(MalformedArgumentsException::class);
        ValidationService::validateOffline($this->context(), '<not xml');
    }

    public function testEmptyValidationFileIsRejected()
    {
        $this->expectException(MalformedArgumentsException::class);
        ValidationService::validateOffline($this->context(), '');
    }

    public function testExternalEntitiesAreNotExpanded()
    {
        $xxe = '<?xml version="1.0"?><!DOCTYPE r [<!ENTITY x SYSTEM "file:///etc/hostname">]>'
            . '<netlicensing><items><item type="ProductModuleValidation">'
            . '<property name="productModuleNumber">M1</property><property name="valid">&x;</property>'
            . '</item></items></netlicensing>';

        $result = ValidationService::validateOffline($this->context(null), $xxe);

        $this->assertSame('', $result->getProductModuleValidation('M1')['valid']);
    }

    public function testWithoutPublicKeySignatureIsNotChecked()
    {
        $result = ValidationService::validateOffline($this->context(null), $this->validationFile());

        $this->assertSame('true', $result->getProductModuleValidation('Msb-DEMO')['valid']);
    }
}

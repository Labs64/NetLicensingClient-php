<?php

use NetLicensing\Constants;
use NetLicensing\Context;
use NetLicensing\NetLicensingCurl;
use NetLicensing\NetLicensingService;
use PHPUnit\Framework\TestCase;

class NetLicensingServiceTest extends TestCase
{
    private function configure(Context $context): array
    {
        $service = NetLicensingService::getInstance();
        (new ReflectionMethod($service, 'configure'))->invoke($service, $context);

        return $service->curl()->getOpt(CURLOPT_HTTPHEADER) ?? [];
    }

    private function authorizationHeaders(array $headers): array
    {
        // curl doesn't send headers with an empty value
        return array_values(array_filter($headers,
            fn($header) => stripos($header, 'Authorization:') === 0 && trim(substr($header, 14)) !== ''));
    }

    public function testApiKeyAuthorizationHeader()
    {
        $context = (new Context())->setSecurityMode(Constants::APIKEY_IDENTIFICATION)->setApiKey('secret');

        $this->assertSame(['Authorization: Basic ' . base64_encode('apiKey:secret')],
            $this->authorizationHeaders($this->configure($context)));
    }

    public function testAnonymousContextDoesNotReusePreviousCredentials()
    {
        $this->configure((new Context())->setUsername('demo')->setPassword('demo'));

        $anonymous = (new Context())->setSecurityMode(Constants::ANONYMOUS_IDENTIFICATION);

        $this->assertSame([], $this->authorizationHeaders($this->configure($anonymous)));
    }

    public function testBuildPostDataFlattensArrayValues()
    {
        $query = (new NetLicensingCurl())->buildPostData(['a' => ['1', '2'], 'b' => 'x', 'c.d' => ['3', '4']]);

        $this->assertSame('a=1&a=2&b=x&c.d=3&c.d=4', $query);
    }
}

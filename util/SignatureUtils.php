<?php
/**
 * @author    Labs64 <netlicensing@labs64.com>
 * @license   Apache-2.0
 * @link      https://netlicensing.io
 * @copyright 2017 Labs64 NetLicensing
 */

namespace NetLicensing;

use Exception;
use FR3D\XmlDSig\Adapter\XmlseclibsAdapter;

class SignatureUtils
{
    /**
     * @param Context $context
     * @param $response
     * @throws BadSignatureException
     */
    static public function check(Context $context, $response)
    {
        if ($context->getPublicKey()) {
            try {
                $xmlDsig = new XmlseclibsAdapter();
                $xmlDsig->setPublicKey($context->getPublicKey());
                $xmlDsig->setDigestAlgorithm(XmlseclibsAdapter::RSA_SHA1);
                $valid = $xmlDsig->verify($response);

                if (!$valid) {
                    throw new BadSignatureException("Response signature verification failure");
                }
            } catch (Exception $e) {
                throw new BadSignatureException($e->getMessage());
            }
        }
    }
}
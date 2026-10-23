<?php
/**
 * @author    Labs64 <netlicensing@labs64.com>
 * @license   Apache-2.0
 * @link      https://netlicensing.io
 * @copyright 2017 Labs64 NetLicensing
 */

namespace NetLicensing;

use DOMDocument;
use Exception;
use RobRichards\XMLSecLibs\XMLSecurityDSig;
use RobRichards\XMLSecLibs\XMLSecurityKey;

class SignatureUtils
{
    /**
     * Verifies the XML digital signature of a NetLicensing response against the public key set in the context.
     * Verification is skipped when the context has no public key.
     *
     * @param Context $context
     * @param DOMDocument $response
     * @throws BadSignatureException
     */
    static public function check(Context $context, DOMDocument $response): void
    {
        if (!$context->getPublicKey()) {
            return;
        }

        try {
            $dSig = new XMLSecurityDSig();

            if (!$dSig->locateSignature($response)) {
                throw new BadSignatureException('Signature DOM element not found.');
            }

            $dSig->canonicalizeSignedInfo();

            $key = new XMLSecurityKey(XMLSecurityKey::RSA_SHA1, ['type' => 'public']);
            $key->loadKey($context->getPublicKey());

            // check signature value, then the digest of the referenced (signed) data
            if ($dSig->verify($key) !== 1 || !$dSig->validateReference()) {
                throw new BadSignatureException('Response signature verification failure');
            }
        } catch (BadSignatureException $e) {
            throw $e;
        } catch (Exception $e) {
            throw new BadSignatureException($e->getMessage(), 0, $e);
        }
    }
}

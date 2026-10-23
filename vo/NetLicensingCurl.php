<?php
/**
 * @author    Labs64 <netlicensing@labs64.com>
 * @license   Apache-2.0
 * @link      https://netlicensing.io
 * @copyright 2017 Labs64 NetLicensing
 */

namespace NetLicensing;


use Curl\Curl;
use ErrorException;

class NetLicensingCurl extends Curl
{
    /**
     * Build Post Data
     *
     * @access public
     * @param  $data
     *
     * @return array|string
     * @throws ErrorException
     */
    public function buildPostData($data)
    {
        $query = parent::buildPostData($data);

        foreach ($data as $key => $value) {
            if (is_array($value)) {
                // send array values as repeated keys (key=1&key=2) instead of key[0]=1&key[1]=2
                $encodedKey = urlencode($key);
                $query = preg_replace('/(^|&)' . preg_quote($encodedKey, '/') . '%5B\\d*%5D=/i', '${1}' . $encodedKey . '=', $query);
            }
        }

        return $query;
    }


    public function toArray(): array
    {
        return [
            'error' => $this->error,
            'errorCode' => $this->errorCode,
            'errorMessage' => $this->errorMessage,
            'curlError' => $this->curlError,
            'curlErrorCode' => $this->curlErrorCode,
            'curlErrorMessage' => $this->curlErrorMessage,
            'httpError' => $this->httpError,
            'httpStatusCode' => $this->httpStatusCode,
            'httpErrorMessage' => $this->httpErrorMessage,
            'url' => $this->url,
            'effectiveUrl' => $this->effectiveUrl,
            'requestHeaders' => $this->requestHeaders,
            'responseHeaders' => $this->responseHeaders,
            'rawResponseHeaders' => $this->rawResponseHeaders,
            'response' => $this->response,
            'rawResponse' => $this->rawResponse,
        ];
    }
}

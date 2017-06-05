<?php
/**
 * @author    Labs64 <netlicensing@labs64.com>
 * @license   Apache-2.0
 * @link      http://netlicensing.io
 * @copyright 2017 Labs64 NetLicensing
 */

namespace NetLicensing;


use Curl\Curl;

class NetLicensingCurl extends Curl
{
    public $data;
    public $query;


    /**
     * Build Post Data
     *
     * @access public
     * @param  $data
     *
     * @return array|string
     */
    public function buildPostData($data)
    {
        $this->data = $data;

        $query = parent::buildPostData($data);

        foreach ($data as $key => $value) {
            if (is_array($value)) $query = preg_replace('/&' . $key . '%5B%5D=/simU', '&' . $key . '=', $query);
        }

        $this->query = $query;

        return $query;
    }


    public function toArray()
    {
        return [
            'error' => $this->curl->error,
            'errorCode' => $this->curl->errorCode,
            'errorMessage' => $this->curl->errorMessage,
            'curlError' => $this->curl->curlError,
            'curlErrorCode' => $this->curl->curlErrorCode,
            'curlErrorMessage' => $this->curl->curlErrorMessage,
            'httpError' => $this->curl->httpError,
            'httpStatusCode' => $this->curl->httpStatusCode,
            'httpErrorMessage' => $this->curl->httpErrorMessage,
            'baseUrl' => $this->curl->baseUrl,
            'url' => $this->curl->url,
            'effectiveUrl' => $this->curl->effectiveUrl,
            'requestHeaders' => $this->curl->requestHeaders,
            'responseHeaders' => $this->curl->responseHeaders,
            'rawResponseHeaders' => $this->curl->rawResponseHeaders,
            'response' => $this->curl->response,
            'rawResponse' => $this->curl->rawResponse,
            'data' => $this->curl->data,
            'query' => $this->curl->query,
        ];
    }
}
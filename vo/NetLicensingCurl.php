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
}
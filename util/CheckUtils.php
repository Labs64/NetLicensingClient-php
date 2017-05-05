<?php
/**
 * @author    Labs64 <netlicensing@labs64.com>
 * @license   Apache-2.0
 * @link      http://netlicensing.io
 * @copyright 2017 Labs64 NetLicensing
 */

namespace NetLicensing;


class CheckUtils
{
    /**
     * Ensures that an object reference passed as a parameter to the calling method is not null.
     *
     * param to check
     * @param $parameter
     *
     * name of the parameter
     * @param $parameterName
     *
     * if parameter is null
     * @throws MalformedArgumentsException
     */
    public static function paramNotNull($parameter, $parameterName)
    {
        if (is_null($parameter)) throw new MalformedArgumentsException(sprintf("Parameter '%s' cannot be null", $parameterName));
    }

    /**
     * Ensures that a string passed as a parameter to the calling method is not null or empty.
     *
     * param to check
     * @param $parameter
     *
     * name of the parameter
     * @param $parameterName
     *
     * if parameter is null or empty
     * @throws MalformedArgumentsException
     */
    public static function paramNotEmpty($parameter, $parameterName)
    {
        if (empty($parameter)) throw new MalformedArgumentsException(sprintf("Parameter '%s' cannot be null or empty string", $parameterName));
    }
}
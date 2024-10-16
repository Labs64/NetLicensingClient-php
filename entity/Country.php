<?php
/**
 * @author    Labs64 <netlicensing@labs64.com>
 * @license   Apache-2.0
 * @link      https://netlicensing.io
 * @copyright 2017 Labs64 NetLicensing
 */

namespace NetLicensing;

/**
 * NetLicensing Country entity.
 *
 * Properties visible via NetLicensing API:
 *
 * @property string $code
 *
 * @property string $name
 *
 * @property double $vatPercent
 *
 * @property boolean $isEu
 *
 * @method string  getCode($default = null)
 * @method string  getName($default = null)
 * @method double  getVatPercent($default = null)
 * @method boolean getIsEu($default = null)
 * @method Country setCode(string $code)
 * @method Country setName(string $name)
 * @method Country setVatPercent(double $vatPercent)
 * @method Country setIsEu(bool $isEu)
 *
 * @package NetLicensing
 */
class Country extends BaseEntity
{
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected array $casts = [
        'vatPercent' => 'double',
        'isEu' => 'boolean_string',
    ];
}

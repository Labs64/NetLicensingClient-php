<?php
/**
 * @author    Labs64 <netlicensing@labs64.com>
 * @license   Apache-2.0
 * @link      http://netlicensing.io
 * @copyright 2016 Labs64 NetLicensing
 */
namespace NetLicensing;


class Transaction extends BaseEntity
{
    /**
     * Transaction still running.
     */
    const STATUS_PENDING = 'PENDING';

    /**
     * Transaction is closed.
     */
    const STATUS_CLOSED = 'CLOSED';

    /**
     * Transaction is cancelled.
     */
    const STATUS_CANCELLED = 'CANCELLED';

    /**
     * Shop transaction.
     */
    const SOURCE_SHOP = 'SHOP';


    public function __construct(array $properties = array())
    {
        $this->_setProperties($properties);
    }

    public function getNumber($default = '')
    {
        return $this->_getProperty('number', $default);
    }

    public function setLicenseeNumber($licensee_number, $refresh = FALSE)
    {
        $this->_setProperty('licenseeNumber', $licensee_number, $refresh);
    }

    public function getLicenseeNumber($default = '')
    {
        return $this->_getProperty('licenseeNumber', $default);
    }

    public function setActive($state, $refresh = FALSE)
    {
        if (is_bool($state)) $state = ($state) ? 'true' : 'false';

        $this->_setProperty('active', $state, $refresh);
    }

    public function getActive()
    {
        return ($this->_getProperty('active') == 'true') ? TRUE : FALSE;
    }

    public function setStatus($status)
    {
        $this->_setProperty('status', $status);
    }

    public function getStatus($default = '')
    {
        return $this->_getProperty('status', $default);
    }

    public function setSource($source)
    {
        $this->_setProperty('source', $source);
    }

    public function getSource($default = '')
    {
        return $this->_getProperty('source', $default);
    }


} 

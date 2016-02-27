<?php
/**
 * @author    Labs64 <netlicensing@labs64.com>
 * @license   Apache License, Version 2.0
 * @link      http://netlicensing.io
 * @copyright 2016 Labs64 NetLicensing
 */
namespace NetLicensing;

class ProductModuleService extends BaseEntityService
{
    const SERVICE_URL = '/productmodule';

    public static function connect(NetLicensingAPI $nlic_connect)
    {
        return new ProductModuleService($nlic_connect);
    }

    public function getList()
    {
        return $this->_getList($this->nlic_connect);
    }

    /**
     * @param $number
     * @return ProductModule|false
     * @throws NetLicensingException
     */
    public function get($number)
    {
        return $this->_get($number, $this->nlic_connect);
    }

    public function create(ProductModule $product_module)
    {
        return $this->_create($product_module, $this->nlic_connect);
    }

    public function update(ProductModule $product_module)
    {
        return $this->_update($product_module, $this->nlic_connect);
    }

    public function delete($number, $force_cascade = FALSE)
    {
        return $this->_delete($number, $this->nlic_connect, $force_cascade);
    }

    protected function _getNewEntity()
    {
        return new ProductModule();
    }

    protected function _getServiceUrlPart()
    {
        return self::SERVICE_URL;
    }
}

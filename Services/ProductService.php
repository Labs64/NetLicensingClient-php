<?php
/**
 * @author    Labs64 <netlicensing@labs64.com>
 * @license   Apache-2.0
 * @link      http://netlicensing.io
 * @copyright 2016 Labs64 NetLicensing
 */
namespace NetLicensing;

class ProductService extends BaseEntityService
{

    const SERVICE_URL = '/product';

    public static function connect(NetLicensingAPI $nlic_connect)
    {
        return new ProductService($nlic_connect);
    }

    public function getList()
    {
        return $this->_list($this->nlic_connect);
    }

    public function get($number)
    {
        return $this->_get($number, $this->nlic_connect);
    }

    public function create(Product $product_module)
    {
        return $this->_create($product_module, $this->nlic_connect);
    }

    public function update(Product $product_module)
    {
        return $this->_update($product_module, $this->nlic_connect);
    }

    public function delete($number, $force_cascade = FALSE)
    {
        return $this->_delete($number, $this->nlic_connect, $force_cascade);
    }

    protected function _createEntity()
    {
        return new Product();
    }

    protected function _getServiceUrl()
    {
        return self::SERVICE_URL;
    }
} 

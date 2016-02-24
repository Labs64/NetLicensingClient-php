<?php

/**
 * @author    Labs64 <info@labs64.com>
 * @license   GPL-2.0+
 * @link      http://www.labs64.com
 * @copyright 2015 Labs64
 */
namespace NetLicensing;


class ProductService extends BaseEntityService {

    const SERVICE_URL = '/product';

    public static function connect(NetLicensingAPI $nlic_connect)
    {
        return new ProductService($nlic_connect);
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

    protected function _getNewEntity()
    {
        return new Product();
    }

    protected function _getServiceUrlPart()
    {
        return self::SERVICE_URL;
    }
} 

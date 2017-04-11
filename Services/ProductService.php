<?php
/**
 * @author    Labs64 <netlicensing@labs64.com>
 * @license   Apache-2.0
 * @link      http://netlicensing.io
 * @copyright 2016 Labs64 NetLicensing
 */
namespace NetLicensing;
/**
 * PHP representation of the Product Service. See NetLicensingAPI for details:
 * https://www.labs64.de/confluence/display/NLICPUB/Product+Services
 *
 * Class ProductService
 * @package NetLicensing
 */
class ProductService extends BaseEntityService
{

    const SERVICE_URL = '/product';

    /**
     * @param NetLicensingAPI $nlic_connect
     * @return ProductService
     */
    public static function connect(NetLicensingAPI $nlic_connect)
    {
        return new ProductService($nlic_connect);
    }

    /**
     * Returns all products of a vendor. See NetLicensingAPI for details:
     * https://www.labs64.de/confluence/display/NLICPUB/Product+Services#ProductServices-Productslist
     *
     * @return array
     */
    public function getList()
    {
        return $this->_list($this->nlic_connect);
    }

    /**
     * Gets product by its number. See NetLicensingAPI for details:
     * https://www.labs64.de/confluence/display/NLICPUB/Product+Services#ProductServices-Getproduct
     *
     * @param $number
     * @return bool
     */
    public function get($number)
    {
        return $this->_get($number, $this->nlic_connect);
    }

    /**
     * Creates new product object with given properties. See NetLicensingAPI for details:
     * https://www.labs64.de/confluence/display/NLICPUB/Product+Services#ProductServices-Createproduct
     *
     * @param Product $product_module
     * @return bool
     */
    public function create(Product $product_module)
    {
        return $this->_create($product_module, $this->nlic_connect);
    }

    /**
     * Updates product properties. See NetLicensingAPI for details:
     * https://www.labs64.de/confluence/display/NLICPUB/Product+Services#ProductServices-Updateproduct
     *
     * @param Product $product_module
     * @return bool
     */
    public function update(Product $product_module)
    {
        return $this->_update($product_module, $this->nlic_connect);
    }

    /**
     * Deletes product. See NetLicensingAPI for details:
     * https://www.labs64.de/confluence/display/NLICPUB/Product+Services#ProductServices-Deleteproduct
     *
     * @param $number
     * @param bool $force_cascade
     * @return bool
     */
    public function delete($number, $force_cascade = FALSE)
    {
        return $this->_delete($number, $this->nlic_connect, $force_cascade);
    }

    /**
     * @return Product
     */
    protected function _createEntity()
    {
        return new Product();
    }

    /**
     * @return string
     */
    protected function _getServiceUrl()
    {
        return self::SERVICE_URL;
    }
} 

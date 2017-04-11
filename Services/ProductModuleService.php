<?php
/**
 * @author    Labs64 <netlicensing@labs64.com>
 * @license   Apache-2.0
 * @link      http://netlicensing.io
 * @copyright 2016 Labs64 NetLicensing
 */
namespace NetLicensing;

/**
 * PHP representation of the ProductModule Service. See NetLicensingAPI for details:
 * https://www.labs64.de/confluence/display/NLICPUB/Product+Module+Services
 *
 * Class ProductModuleService
 * @package NetLicensing
 */
class ProductModuleService extends BaseEntityService
{
    const SERVICE_URL = '/productmodule';

    /**
     * @param NetLicensingAPI $nlic_connect
     * @return ProductModuleService
     */
    public static function connect(NetLicensingAPI $nlic_connect)
    {
        return new ProductModuleService($nlic_connect);
    }

    /**
     * Returns all product modules of a vendor. See NetLicensingAPI for details:
     * https://www.labs64.de/confluence/display/NLICPUB/Product+Module+Services#ProductModuleServices-Productmoduleslist
     *
     * @return array
     */
    public function getList()
    {
        return $this->_list($this->nlic_connect);
    }

    /**
     * Gets product module by its number. See NetLicensingAPI for details:
     * https://www.labs64.de/confluence/display/NLICPUB/Product+Module+Services#ProductModuleServices-Getproductmodule
     *
     * @param $number
     * @return bool
     */
    public function get($number)
    {
        return $this->_get($number, $this->nlic_connect);
    }

    /**
     * Creates new ProductModel object with given properties. See NetLicensingAPI for details:
     * https://www.labs64.de/confluence/display/NLICPUB/Product+Module+Services#ProductModuleServices-Createproductmodule
     *
     * @param ProductModule $product_module
     * @return bool
     */
    public function create(ProductModule $product_module)
    {
        return $this->_create($product_module, $this->nlic_connect);
    }

    /**
     * Updates product module properties. See NetLicensingAPI for details:
     * https://www.labs64.de/confluence/display/NLICPUB/Product+Module+Services#ProductModuleServices-Updateproductmodule
     *
     * @param ProductModule $product_module
     * @return bool
     */
    public function update(ProductModule $product_module)
    {
        return $this->_update($product_module, $this->nlic_connect);
    }

    /**
     * Deletes product module. See NetLicensingAPI for details:
     * https://www.labs64.de/confluence/display/NLICPUB/Product+Module+Services#ProductModuleServices-Deleteproductmodule
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
     * @return ProductModule
     */
    protected function _createEntity()
    {
        return new ProductModule();
    }

    /**
     * @return string
     */
    protected function _getServiceUrl()
    {
        return self::SERVICE_URL;
    }
}

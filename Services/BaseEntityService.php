<?php
/**
 * @author    Labs64 <netlicensing@labs64.com>
 * @license   Apache-2.0
 * @link      http://netlicensing.io
 * @copyright 2016 Labs64 NetLicensing
 */
namespace NetLicensing;

abstract class BaseEntityService
{
    /**
     * @var NetLicensingAPI
     */
    protected $nlic_connect;

    abstract protected function _getNewEntity();

    abstract protected function _getServiceUrlPart();

    public function __construct(NetLicensingAPI $nlic_connect)
    {
        $this->nlic_connect = $nlic_connect;
    }

    protected function _getList(NetLicensingAPI $nlic_connect)
    {
        $list = array();

        $service_url = $this->_getServiceRequestPartUrl();
        $response = $nlic_connect->get($service_url);
        $properties_array = NetLicensingAPI::getPropertiesByXml($response);

        $entity = $this->_getNewEntity();

        if (!is_object($entity)) throw new NetLicensingException('Invalid entity, expect to be a object, ' . gettype($entity) . ' given');

        if (!$entity instanceof BaseEntity) throw new NetLicensingException('Invalid entity ' . get_class($entity) . ', entity must be instanceof BaseEntity');

        if ($properties_array) {
            foreach ($properties_array as $properties) {
                /** @var  $entity BaseEntity */
                $entity = $this->_getNewEntity();
                $entity->setProperties($properties, TRUE);
                $list[$properties['number']] = $entity;
            }
        }

        return $list;
    }

    protected function _get($number, NetLicensingAPI $nlic_connect)
    {
        $entity = $this->_getNewEntity();
        $number = (string)$number;

        if (!is_object($entity)) throw new NetLicensingException('Invalid entity, expect to be a object, ' . gettype($entity) . ' given');

        if (!$entity instanceof BaseEntity) throw new NetLicensingException('Invalid entity ' . get_class($entity) . ', entity must be instanceof BaseEntity');

        if (!$number) throw new NetLicensingException('Missing ' . get_class($entity) . ' number ');

        $service_url = $this->_getServiceRequestPartUrl();
        $response = $nlic_connect->get($service_url . '/' . $number);
        $properties_array = NetLicensingAPI::getPropertiesByXml($response);

        if (empty($properties_array)) return FALSE;

        $properties = reset($properties_array);
        $entity->setProperties($properties, TRUE);

        return $entity;
    }

    protected function _create($entity, NetLicensingAPI $nlic_connect)
    {
        if (!is_object($entity)) throw new NetLicensingException('Invalid entity, expect to be a object, ' . gettype($entity) . ' given');

        if (!$entity instanceof BaseEntity) throw new NetLicensingException('Invalid entity ' . get_class($entity) . ', entity must be instanceof BaseEntity');

        $service_url = $this->_getServiceRequestPartUrl();
        $response = $nlic_connect->post($service_url, $entity->getProperties());
        $properties_array = NetLicensingAPI::getPropertiesByXml($response);

        if (empty($properties_array)) return FALSE;

        $properties = reset($properties_array);
        $entity->setProperties($properties, TRUE);

        return $entity;
    }

    protected function _update($entity, NetLicensingAPI $nlic_connect)
    {
        if (!is_object($entity)) throw new NetLicensingException('Invalid entity, expect to be a object, ' . gettype($entity) . ' given');

        if (!$entity instanceof BaseEntity) throw new NetLicensingException('Invalid entity ' . get_class($entity) . ', entity must be instanceof BaseEntity');

        if (!$entity->getOldProperty('number')) throw new NetLicensingException('The ' . get_class($entity) . ' cannot be updated because property "number" is missing or ProductModule is new.');

        $service_url = $this->_getServiceRequestPartUrl();
        $response = $nlic_connect->post($service_url . '/' . $entity->getOldProperty('number'), $entity->getProperties());
        $properties_array = NetLicensingAPI::getPropertiesByXml($response);

        if (empty($properties_array)) return FALSE;

        $properties = reset($properties_array);
        $entity->setProperties($properties, TRUE);
        return $entity;
    }

    protected function _delete($number, NetLicensingAPI $nlic_connect, $force_cascade = FALSE)
    {
        $params = array();
        $service_url = $this->_getServiceRequestPartUrl();

        if ($force_cascade) {
            $params['forceCascade'] = TRUE;
        }

        $response = $nlic_connect->delete($service_url . '/' . $number, $params);

        $status_code = $nlic_connect->getHttpStatusCode();
        return (!empty($status_code) && $status_code == '204') ? TRUE : FALSE;
    }

    protected function _getServiceRequestPartUrl()
    {
        $service_url_part = $this->_getServiceUrlPart();
        if (!$service_url_part) throw new NetLicensingException('Invalid service url part for request');

        return $service_url_part;
    }
}

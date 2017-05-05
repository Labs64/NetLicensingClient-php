<?php
/**
 * @author    Labs64 <netlicensing@labs64.com>
 * @license   Apache-2.0
 * @link      http://netlicensing.io
 * @copyright 2017 Labs64 NetLicensing
 */

namespace NetLicensing;

/**
 * Defines properties common to all (or most) of other entities.
 *
 * @package NetLicensing\EntitiesNew
 */
abstract class BaseEntity
{
    use Properties;

    /**
     * The primary key for the entity.
     *
     * @var string
     */
    protected $primaryKey = 'number';

    /**
     * Indicates if the entity exists.
     *
     * @var bool
     */
    public $exists = false;

    /**
     *  Create a new entity instance.
     *
     * @param array $properties
     * @param bool $exists
     */
    public function __construct(array $properties = [], $exists = false)
    {
        $this->setProperties($properties, true);

        $this->exists = $exists;
    }

    /**
     * Get the primary key for the entity.
     *
     * @return string
     */
    public function getKeyName()
    {
        return $this->primaryKey;
    }

    /**
     * Get the value of the entity primary key.
     *
     * @return mixed
     */
    public function getKey()
    {
        return $this->getProperty($this->getKeyName());
    }

    /**
     * Dynamically retrieve properties on the entity.
     *
     * @param  string $key
     * @return mixed
     */
    public function __get($key)
    {
        return $this->getProperty($key);
    }

    /**
     * Dynamically set properties on the entity.
     *
     * @param  string $key
     * @param  mixed $value
     * @return void
     */
    public function __set($key, $value)
    {
        $this->setProperty($key, $value);
    }

    /**
     * Determine if an property on the entity.
     *
     * @param  string $key
     * @return bool
     */
    public function __isset($key)
    {
        return !is_null($this->getProperty($key));
    }

    /**
     * Unset an property on the entity.
     *
     * @param  string $key
     * @return void
     */
    public function __unset($key)
    {
        unset($this->properties[$key]);
    }

    /**
     * Handle dynamic method calls into the entity.
     *
     * @param  string $method
     * @param  array $parameters
     * @return mixed
     */
    public function __call($method, $parameters)
    {
        //convert method to snake case
        $delimiter = '_';
        $method = preg_replace('/\s+/u', '', $method);
        $method = mb_strtolower(preg_replace('/(.)(?=[A-Z])/u', '$1' . $delimiter, $method), 'UTF-8');

        $methodParts = explode($delimiter, $method);

        //check if need set or get attributes
        if (in_array($methodParts[0], ['get', 'set'])) {

            //get attribute name
            $key = array_slice($methodParts, 1);
            $key = implode('_', $key);
            $key = lcfirst(str_replace(' ', '', ucwords(str_replace(['-', '_'], ' ', $key))));

            array_unshift($parameters, $key);

            //call getAttribute
            if ($methodParts[0] == 'get') return $this->getProperty(...$parameters);

            //call setAttribute
            if ($methodParts[0] == 'set') return $this->setProperty(...$parameters);
        }

        //trigger error if method undefined
        trigger_error('Call to undefined method ' . __CLASS__ . '::' . $method . '()', E_USER_ERROR);
    }

    /**
     * Convert the entity instance to an array.
     *
     * @return array
     */
    public function toArray()
    {
        return $this->properties;
    }

    /**
     * Convert the entity instance to an array.
     */
    public function asPropertiesMap()
    {
        return $this->properties;
    }

    /**
     * Convert the entity instance to JSON.
     *
     * @param  int $options
     * @return string
     */
    public function toJson($options = 0)
    {
        return json_encode($this->jsonSerialize(), $options);
    }

    /**
     * Convert the object into something JSON serializable.
     *
     * @return array
     */
    public function jsonSerialize()
    {
        return $this->toArray();
    }

    /**
     * Convert the entity to its string representation.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->toJson();
    }
}
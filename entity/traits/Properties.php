<?php
/**
 * @author    Labs64 <netlicensing@labs64.com>
 * @license   Apache-2.0
 * @link      http://netlicensing.io
 * @copyright 2017 Labs64 NetLicensing
 */

namespace NetLicensing;


use DateTimeInterface;

trait Properties
{
    /**
     * The entity properties.
     *
     * @var array
     */
    protected $properties = [];

    /**
     * The entity properties original state.
     *
     * @var array
     */
    protected $original = [];

    /**
     * The properties that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [];

    /**
     * Get an property from the entity.
     *
     * @param  string $property
     * @param  mixed $default
     * @return mixed
     */
    public function getProperty($property, $default = null)
    {
        if (!$property) return $default;

        $value = isset($this->properties[$property]) ? $this->properties[$property] : $default;

        // If the attribute exists within the cast array, we will convert it to
        // an appropriate native PHP type dependant upon the associated value
        // given with the key in the pair. Dayle made this comment line up.
        if ($this->hasCast($property)) {
            return $this->castGetProperty($property, $value);
        }

        return $value;
    }

    /**
     * Get all of the current properties on the entity.
     *
     * @return array
     */
    public function getProperties()
    {
        return $this->properties;
    }

    /**
     * Set a given property on the entity.
     *
     * @param  string $property
     * @param  mixed $value
     * @return $this
     */
    public function setProperty($property, $value)
    {
        $this->properties[$property] = ($this->hasCast($property)) ? $this->castSetProperty($property, $value) : $value;
        return $this;
    }

    /**
     * Alias for setProperty
     *
     * @param $property
     * @param $value
     * @return $this
     */
    public function addProperty($property, $value)
    {
        return $this->setProperty($property, $value);
    }


    /**
     * Set the array of entity properties.
     *
     * @param  array $properties
     * @param  bool $sync
     * @return $this
     */
    public function setProperties(array $properties, $sync = false)
    {
        $this->properties = [];

        foreach ($properties as $property => $value) {
            $setMethod = 'set' . ucfirst($property);
            if (method_exists($this, $setMethod)) {
                $this->$setMethod($value);
            } else {
                $this->setProperty($property, $value);
            }
        }

        if ($sync) {
            $this->syncOriginal();
        }

        return $this;
    }

    /**
     * Remove property
     *
     * @param $property
     * @return $this
     */
    public function removeProperty($property)
    {
        unset($this->properties[$property]);
        return $this;
    }

    /**
     * Get the entity original attribute values.
     *
     * @param  string|null $property
     * @param  mixed $default
     * @return mixed|array
     */
    public function getOriginal($property = null, $default = null)
    {
        if (is_null($property)) return $this->original;

        return isset($this->original[$property]) ? $this->original[$property] : $default;
    }


    /**
     * Sync the original attributes with the current.
     *
     * @return $this
     */
    public function syncOriginal()
    {
        $this->original = $this->properties;

        return $this;
    }

    /**
     * Sync a single original attribute with its current value.
     *
     * @param  string $property
     * @return $this
     */
    public function syncOriginalProperty($property)
    {
        $this->original[$property] = $this->properties[$property];

        return $this;
    }

    /**
     * Determine whether an attribute should be cast to a native type.
     *
     * @param  string $property
     * @param  array|string|null $types
     * @return bool
     */
    public function hasCast($property, $types = null)
    {
        if (array_key_exists($property, $this->casts)) {
            return $types ? in_array($this->getCastType($property), (array)$types, true) : true;
        }

        return false;
    }

    /**
     * Get the type of cast for a entity attribute.
     *
     * @param  string $property
     * @return string
     */
    protected function getCastType($property)
    {
        return trim(strtolower($this->casts[$property]));
    }

    /**
     * Determine if the entity or given property(s) have remained the same.
     *
     * @param  array|string|null $attributes
     * @return bool
     */
    public function isClean($attributes = null)
    {
        return !$this->isDirty(...func_get_args());
    }

    /**
     * Determine if the entity or given property(s) have been modified.
     *
     * @param  array|string|null $properties
     * @return bool
     */
    public function isDirty($properties = null)
    {
        $dirty = $this->getDirty();

        // If no specific attributes were provided, we will just see if the dirty array
        // already contains any attributes. If it does we will just return that this
        // count is greater than zero. Else, we need to check specific attributes.
        if (is_null($properties)) {
            return count($dirty) > 0;
        }

        $properties = is_array($properties)
            ? $properties : func_get_args();

        // Here we will spin through every attribute and see if this is in the array of
        // dirty attributes. If it is, we will return true and if we make it through
        // all of the attributes for the entire array we will return false at end.
        foreach ($properties as $property) {
            if (array_key_exists($property, $dirty)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Get the properties that have been changed since last sync.
     *
     * @return array
     */
    public function getDirty()
    {
        $dirty = [];

        foreach ($this->properties as $property => $value) {
            if (!array_key_exists($property, $this->original)) {
                $dirty[$property] = $value;
            } elseif ($value !== $this->original[$property] &&
                !$this->originalIsNumericallyEquivalent($property)
            ) {
                $dirty[$property] = $value;
            }
        }

        return $dirty;
    }

    /**
     * Determine if the new and old values for a given key are numerically equivalent.
     *
     * @param  string $property
     * @return bool
     */
    protected function originalIsNumericallyEquivalent($property)
    {
        $current = $this->properties[$property];

        $original = $this->original[$property];

        // This method checks if the two values are numerically equivalent even if they
        // are different types. This is in case the two values are not the same type
        // we can do a fair comparison of the two values to know if this is dirty.
        return is_numeric($current) && is_numeric($original)
            && strcmp((string)$current, (string)$original) === 0;
    }

    /**
     * Cast an property to a native PHP type.
     *
     * @param  string $property
     * @param  mixed $value
     * @return mixed
     */
    protected function castGetProperty($property, $value)
    {
        if (is_null($value)) {
            return $value;
        }

        switch ($this->getCastType($property)) {
            case 'int':
            case 'integer':
                return (int)$value;
            case 'real':
            case 'float':
            case 'double':
                return (float)$value;
            case 'string':
                return (string)$value;
            case 'bool':
            case 'boolean':
                return (bool)$value;
            case 'boolean_string':
                return ($value == 'true');
            case 'object':
                return json_decode($value, false);
            case 'array':
            case 'json':
                return json_decode($value, true);
            case 'datetime':
                return new \DateTime($value);
            default:
                return $value;
        }
    }

    /**
     * Cast an property to a native PHP type.
     *
     * @param  string $property
     * @param  mixed $value
     * @return mixed
     */
    protected function castSetProperty($property, $value)
    {
        if (is_null($value)) {
            return $value;
        }

        switch ($this->getCastType($property)) {
            case 'int':
            case 'integer':
                return (int)$value;
            case 'real':
            case 'float':
            case 'double':
                return (float)$value;
            case 'string':
                return (string)$value;
            case 'bool':
            case 'boolean':
                return (bool)$value;
            case 'boolean_string':
                return ($value && $value !== 'false') ? 'true' : 'false';
            case 'object':
                return json_decode($value, false);
            case 'array':
            case 'json':
                return json_decode($value, true);
            case 'datetime':
                return $this->asDateTime($value);
            default:
                return $value;
        }
    }

    /**
     * Decode the given JSON back into an array or object.
     *
     * @param  string $value
     * @param  bool $asObject
     * @return mixed
     */
    protected function fromJson($value, $asObject = false)
    {
        return json_decode($value, !$asObject);
    }

    /**
     * Return a DatetTime/timestamp as time string.
     *
     * @param $value
     * @return string
     */
    protected function asDateTime($value)
    {

        // If the value is already a DateTime instance, we will just skip the rest of
        // these checks since they will be a waste of time, and hinder performance
        // when checking the field. We will just return the DateTime right away.
        if ($value instanceof DateTimeInterface) {
            return $value->format('Y-m-d\TH:i:sP');
        }

        // If this value is an integer, we will assume it is a UNIX timestamp's value
        // and format a Carbon object from this timestamp. This allows flexibility
        // when defining your date fields as they might be UNIX timestamps here.
        if (is_numeric($value)) {
            return (new \DateTime())->setTimestamp($value)->format('Y-m-d\TH:i:sP');
        }

        return $value;
    }
}
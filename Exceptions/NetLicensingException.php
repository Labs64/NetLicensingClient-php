<?php

namespace NetLicensing;

use Exception;

class NetLicensingException extends Exception
{
    public function __toString()
    {
        return get_class($this) . ": [" . $this->getCode() . "]: " . $this->getMessage() . "\n";
    }
}
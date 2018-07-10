<?php

namespace Pyrocmsapi\FieldDataAccessor;

use Anomaly\Streams\Platform\Addon\FieldType\FieldType as FT;

class FieldType
{
    protected $fieldType;

    public function __construct(FT $fieldType)
    {
        $this->fieldType = $fieldType;
    }


    public function getData()
    {
        return $this->fieldType->getValue();
    }
}

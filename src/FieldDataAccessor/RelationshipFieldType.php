<?php

namespace Pyrocmsapi\FieldDataAccessor;

use Anomaly\Streams\Platform\Addon\FieldType\FieldType;

class RelationshipFieldType extends FieldType
{
    protected $fieldType;

    public function __construct(FieldType $fieldType)
    {
        $this->fieldType = $fieldType;
    }

    public function getData()
    {
        return $this->fieldType->getRelation()->get()->toArray();
    }
}

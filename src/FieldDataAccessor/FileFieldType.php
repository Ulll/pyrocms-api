<?php

namespace Pyrocmsapi\FieldDataAccessor;

use Anomaly\Streams\Platform\Addon\FieldType\FieldType;
use Pyrocmsapi\Traits\FileHander;
use Anomaly\FilesModule\File\Contract\FileInterface;

class FileFieldType extends FieldType
{
    use FileHander;

    protected $fieldType;

    public function __construct(FieldType $fieldType)
    {
        $this->fieldType = $fieldType;
    }


    public function getData()
    {
        $file = $this->fieldType->getRelation()->first();
        $data = $this->getSizeFile($file);
        return $data;
    }
}

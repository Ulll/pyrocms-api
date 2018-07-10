<?php

namespace Pyrocmsapi\FieldDataAccessor;

use Anomaly\Streams\Platform\Addon\FieldType\FieldType;
use Pyrocmsapi\Traits\FileHander;
use Anomaly\FilesModule\File\Contract\FileInterface;

class FilesFieldType extends FieldType
{
    use FileHander;

    protected $fieldType;

    public function __construct(FieldType $fieldType)
    {
        $this->fieldType = $fieldType;
    }

    public function getData()
    {
        $FileCollection = $this->fieldType->getRelation()->get();

        $newData = $FileCollection->map(function (FileInterface $file) {
            return $this->getSizeFile($file);
        });
        return $newData;
    }
}

<?php

namespace Pyrocmsapi\Traits;
use Anomaly\FilesModule\File\Contract\FileInterface;

/**
 * 处理图片
 */
trait FileHander
{
    function getSizeFile(FileInterface $file)
    {
        $ret['url']       = $file->url();
        $ret['name']      = $file->getName();
        $ret['type']      = $file->type();
        $ret['mimetype']  = $file->getMimeType();
        $ret['width']     = $file->getWidth();
        $ret['height']    = $file->getHeight();
        $ret['size']      = $file->getSize();
        $ret['extension'] = $file->getExtension();
        return $ret;
    }
}

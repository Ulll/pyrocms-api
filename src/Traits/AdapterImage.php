<?php

namespace Pyrocmsapi\Traits;
use Anomaly\FilesModule\File\Contract\FileInterface;
use Anomaly\FilesModule\File\FileCollection;
/**
 * 处理图片
 */
trait AdapterImage
{
    /**
     * 从一系列图片中选出最匹配宽高比的图片
     * @method get_adapter_size_image
     * @param  FileCollection $images 待选图片
     * @param  array $thumb_sizes 目标尺寸
     * @return array
     */
    protected function get_adapter_size_image(FileCollection $images, array $thumb_sizes = ['original'])
    {
        //空图片
        if ($images->isEmpty()) {
            return new \stdClass;
        }
        $ret_images = array();
        //获取所有供选图片的宽高比
        foreach ($images as $k=>$image) {
            $orig_width    = $image->getWidth();
            $orig_height   = $image->getHeight();
            $orig_ratio    = $orig_width/$orig_height;
            $orig_ratios[] = $orig_ratio;
        }
        //默认返回裁图尺寸
        if (!$thumb_sizes) {
            $thumb_sizes = '["original"]';
        }
        //目标尺寸
        foreach ($thumb_sizes as $k=>$size) {
            $sizes = explode('_', $size);
            //不同时限定宽高，则直接取图片组第一张进行等比缩放
            if (count($sizes) != 2 || !$sizes[0] || !$sizes[1]) {
                $ret_images[$size] = array($this->fieldDataImage($images[0]));
            }else {
                //限定宽高，则比对出尺寸最接近的一张图片进行裁剪
                $need_ratio = $sizes[0]/$sizes[1];
                $tmp_dif = 99999;
                $tmp_key = 0;
                foreach ($orig_ratios as $k1=> $v1) {
                    $dif = abs($need_ratio-$v1);
                    if ($dif < $tmp_dif) {
                        $tmp_dif = $dif;
                        $tmp_key = $k1;
                    }
                }
                $ret_images[$size] = array($this->fieldDataImage($images[$tmp_key]));
            }
        }
        return $ret_images;
    }

    /**
     * 文章中图片的返回格式
     * @method fieldDataImage
     * @param  FileInterface  $file
     * @return array
     */
    protected function fieldDataImage(FileInterface $file)
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

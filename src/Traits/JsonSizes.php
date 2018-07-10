<?php

namespace Pyrocmsapi\Traits;

/**
 * 处理图片
 */
trait JsonSizes
{
    function JsonSizes($data)
    {
        $jdata = json_decode($data);
        $is_json = (json_last_error() == JSON_ERROR_NONE);
        if (!$is_json) {
            return false;
        }
        if (!is_array($jdata)) {
            return false;
        }
        if (empty($jdata)) {
            return false;
        }
        return $jdata;
    }
}

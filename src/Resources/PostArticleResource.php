<?php

namespace Pyrocmsapi\Resources;

use Illuminate\Http\Resources\Json\Resource;

class PostArticleResource extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => '2'
        ];
        return parent::toArray($request);
    }
}

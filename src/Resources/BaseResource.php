<?php

namespace Pyrocmsapi\Resources;

use Illuminate\Http\Resources\Json\Resource;

use Pyrocmsapi\Traits\JsonSizes;
use Pyrocmsapi\Traits\AdapterImage;

class BaseResource extends Resource
{
    use JsonSizes;
    use AdapterImage;
}

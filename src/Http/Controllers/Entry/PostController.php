<?php

namespace Pyrocmsapi\Http\Controllers\Entry;

use Anomaly\Streams\Platform\Http\Controller\PublicController;
use Anomaly\PostsModule\Post\Contract\PostRepositoryInterface;

class PostController extends PublicController
{

    /**
     * 获取post列表
     * @method list
     * @return null|\Symfony\Component\HttpFoundation\Response
     */
    public function list(PostRepositoryInterface $posts)
    {
        return $posts->getRecent($this->request->get('limit'));
    }
}

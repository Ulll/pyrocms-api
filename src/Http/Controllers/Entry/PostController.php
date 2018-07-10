<?php

namespace Pyrocmsapi\Http\Controllers\Entry;

use Pyrocmsapi\Repository;

use Pyrocmsapi\Model\PostModel;

use Anomaly\Streams\Platform\Http\Controller\ResourceController;
// use Anomaly\PostsModule\Post\Contract\PostRepositoryInterface;
use Pyrocmsapi\Repository\PostRepository;

class PostController extends ResourceController
{

    /**
     * 获取post列表
     * @method list
     * @return null|\Symfony\Component\HttpFoundation\Response
     */
    public function list(PostRepository $posts)
    {
        return $posts->getIndexList($this->request->get('limit'));
    }

    public function post(PostRepository $posts, $id)
    {
        return $posts->find($id);
    }
}

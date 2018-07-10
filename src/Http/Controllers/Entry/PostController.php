<?php

namespace Pyrocmsapi\Http\Controllers\Entry;

use Anomaly\Streams\Platform\Http\Controller\ResourceController;
use Pyrocmsapi\Repository\PostRepository;

class PostController extends ResourceController
{
    protected $posts;

    public function __construct(PostRepository $posts)
    {
        $this->posts = $posts;
    }

    /**
     * 获取post列表
     * @method list
     * @return null|\Symfony\Component\HttpFoundation\Response
     */
    public function list()
    {
        return $this->posts->getIndexList();
    }

    public function post($id)
    {
        return $this->posts->find($id);
    }
}

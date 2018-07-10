<?php

namespace Pyrocmsapi\Http\Controllers\Entry;

use Anomaly\Streams\Platform\Http\Controller\ResourceController;
use Pyrocmsapi\Repository\PostArticleRepository;

class PostArticleController extends ResourceController
{
    protected $articles;

    public function __construct(PostArticleRepository $article)
    {
        $this->articles = $article;
    }

    /**
     * 获取post列表
     * @method list
     * @return null|\Symfony\Component\HttpFoundation\Response
     */
    public function list()
    {
        return $this->articles->getIndexList();
    }

    public function article($id)
    {
        return $this->articles->find($id);
    }
}

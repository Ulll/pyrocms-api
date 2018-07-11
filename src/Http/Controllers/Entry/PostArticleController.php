<?php

namespace Pyrocmsapi\Http\Controllers\Entry;

use Anomaly\Streams\Platform\Http\Controller\ResourceController;
use Pyrocmsapi\Repository\PostArticleRepository;
use Pyrocmsapi\Resources\PostArticleResource;
use Pyrocmsapi\Resources\PostArticleCollection;

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
     * @return null|PostArticleCollection
     */
    public function list()
    {
        return PostArticleCollection::make($this->articles->getIndexList());
    }

    /**
     * 获取文章详情
     * @method article
     * @param  integer  $id
     * @return PostArticleResource
     */
    public function article($id)
    {
        return PostArticleResource::make($this->articles->find($id));
    }
}

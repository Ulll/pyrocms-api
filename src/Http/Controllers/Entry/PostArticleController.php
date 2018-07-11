<?php

namespace Pyrocmsapi\Http\Controllers\Entry;

use Anomaly\Streams\Platform\Http\Controller\ResourceController;
use Pyrocmsapi\Repository\PostArticleRepository;
use Pyrocmsapi\Resources\PostArticleResource;
use Pyrocmsapi\Resources\PostArticleCollection;
use Illuminate\Http\Request;

class PostArticleController extends ResourceController
{
    protected $articles;

    /**
     * the request object
     * @var Request
     */
    protected $request;


    public function __construct(PostArticleRepository $article, Request $request)
    {
        $this->articles = $article;
        $this->request = $request;
    }

    /**
     * 获取post列表
     * @method list
     * @return null|PostArticleCollection
     */
    public function list()
    {
        $limit  = $this->request->get('limit',10);
        $offset = $this->request->get('offset',0);
        return PostArticleCollection::make($this->articles->getIndexList($limit, $offset));
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

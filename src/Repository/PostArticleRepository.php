<?php

namespace Pyrocmsapi\Repository;


use Anomaly\PostsModule\Type\Contract\TypeRepositoryInterface;
use Anomaly\PostsModule\Post\Contract\PostRepositoryInterface;
use Anomaly\PostsModule\Post\PostRepository;

use Pyrocmsapi\Model\PostModel;
use Illuminate\Http\Request;


/**
 * API专用的post repository
 */
class PostArticleRepository extends PostRepository implements PostRepositoryInterface
{
    /**
     * The post model.
     *
     * @var PostModel
     */
    protected $model;

    /**
     * the request object
     * @var Request
     */
    protected $request;

    /**
     * the post type
     * @var [type]
     */
    protected $type;

    /**
     * Create a new PostRepository instance.
     *
     * @param PostModel $model
     */
    public function __construct(PostModel $model, Request $request, TypeRepositoryInterface $type)
    {
        parent::__construct($model);
        $this->model   = $model;
        $this->request = $request;
        $this->type    = $type->findBySlug('article');
    }

    /**
     * 获取首页文章列表
     * @method getIndexList
     * @param  integer $limit 10
     * @return array
     */
    public function getIndexList($limit, $offset)
    {
        $perpage = $limit;
        $currentPage = intval($offset/$limit)+1;
        return $this->model
            ->live()
            ->where('type_id', $this->type->getId())
            ->skip($offset)
            ->limit($limit)
            ->get();
    }
}

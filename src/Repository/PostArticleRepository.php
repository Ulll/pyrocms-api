<?php

namespace Pyrocmsapi\Repository;

use Anomaly\PostsModule\Post\Contract\PostRepositoryInterface;
use Anomaly\PostsModule\Post\PostRepository;

use Pyrocmsapi\Model\PostArticleModel;
use Illuminate\Http\Request;


/**
 * API专用的post repository
 */
class PostArticleRepository extends PostRepository implements PostRepositoryInterface
{
    /**
     * The post model.
     *
     * @var PostArticleModel
     */
    protected $model;

    /**
     * the request object
     * @var Request
     */
    protected $request;


    /**
     * Create a new PostRepository instance.
     *
     * @param PostArticleModel $model
     */
    public function __construct(PostArticleModel $model, Request $request)
    {
        $this->request = $request;
        $this->model   = $model;
    }
}

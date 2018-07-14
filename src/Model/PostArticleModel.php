<?php

namespace Pyrocmsapi\Model;

use Anomaly\PostsModule\Post\PostModel;
use Pyrocmsapi\Model\Scope\PostTypeScope;
use Anomaly\PostsModule\Type\Contract\TypeRepositoryInterface;
use Anomaly\PostsModule\Type\TypeRepository;
use Anomaly\PostsModule\Type\TypeModel;

/**
 * post model for api
 */
class PostArticleModel extends PostModel {

    protected static function boot()
    {
        parent::boot();

        // static::addGlobalScope(new PostTypeScope(new TypeRepository(new TypeModel), 'article'));
        static::addGlobalScope(new PostTypeScope(app()->make(TypeRepositoryInterface::class, [new TypeModel]), 'article'));
    }
}

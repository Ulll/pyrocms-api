<?php

namespace Pyrocmsapi\Model\Scope;

use Illuminate\Database\Eloquent\Scope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Anomaly\PostsModule\Type\Contract\TypeRepositoryInterface;

class PostTypeScope implements Scope
{
    /**
     * the post type
     * @var [type]
     */
    protected $type;

    public function __construct(TypeRepositoryInterface $type, $slug)
    {
        $this->type = $type;
        $this->slug = $slug;
    }

    /**
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $builder
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @return void
     */
    public function apply(Builder $builder, Model $model)
    {
        return $builder->where('type_id', '=', $this->type->findBySlug($this->slug)->getId());
    }
}

<?php

namespace Pyrocmsapi\Resources;

use Pyrocmsapi;

use Illuminate\Http\Resources\Json\ResourceCollection;
use Pyrocmsapi\Model\PostArticleModel;
use Anomaly\Streams\Platform\Model\Posts\PostsArticlePostsEntryModel;
use Anomaly\FilesModule\File\FileModel;
use Anomaly\FilesModule\File\FileCollection;
use Anomaly\UsersModule\User\Contract\UserRepositoryInterface;


class PostArticleCollection extends ResourceCollection
{
    /**
     * @var array
     */
    protected $withoutFields = [
        'content'
    ];

    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $this->request = $request;
        return [
            'data' => $this->map(function (PostArticleModel $resource) use ($request) {
                return PostArticleResource::make($resource)->hide($this->withoutFields)->toArray($request);
            }),
        ];
    }
}

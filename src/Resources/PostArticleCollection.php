<?php

namespace Pyrocmsapi\Resources;

use Pyrocmsapi;

use Illuminate\Http\Resources\Json\ResourceCollection;
use Pyrocmsapi\Model\PostModel;
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
            'errno'  => 0,
            'errmsg' => '',
            'data'   => $this->map(function (PostModel $resource) use ($request) {
                $data = PostArticleResource::make($resource)->hide($this->withoutFields)->toArray($request);
                return $data['data'];
            }),
        ];
    }
}

<?php

namespace Pyrocmsapi\Resources;

use Illuminate\Http\Resources\Json\Resource;

use Anomaly\UsersModule\User\Contract\UserRepositoryInterface;
use Anomaly\FilesModule\File\FileModel;
use Anomaly\Streams\Platform\Model\Posts\PostsArticlePostsEntryModel;

class PostArticleResource extends BaseResource
{
    /**
     * @var array
     */
    protected $withoutFields = [];

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $this->request = $request;
        $result        = parent::toArray($request);
        $relation      = parent::relationsToArray();
        $article       = array(
            'errno'  => 0,
            'errmsg' => '',
        );
        $article['data'] = $this->filterFields([
            'id'           => $result['id'],
            'title'        => $result['title'],
            'summary'      => $result['summary'],
            'content'      => $relation['entry']['content'],
            'author'       => $this->getAuthor($this->getFieldValue('author')),
            'cover_image'  => $this->getArticleCoverImage(),
            'bookmark_num' => rand(100,300),
        ]);
        return $article;
    }

    /**
     * Remove the filtered keys.
     *
     * @param $array
     * @return array
     */
    protected function filterFields($array)
    {
        return collect($array)->forget($this->withoutFields)->toArray();
    }

    /**
     * 隐藏字段
     * @method hide
     * @param  array  $fields [description]
     * @return [type]         [description]
     */
    public function hide(array $fields)
    {
        $this->withoutFields = $fields;
        return $this;
    }

    /**
     * 获取文章封面图片
     * @method getArticleCoverImage
     * @return array
     */
    public function getArticleCoverImage()
    {
        $entryCollection = $this->resource->entry()->get();
        $entryCollection = $entryCollection->filter(function($item){
            if ($item instanceof PostsArticlePostsEntryModel) {
                return $item;
            }
        });
        $entry = $entryCollection[0];
        //处理封面
        if (!method_exists($entry, 'coverImage')) {
            return new \stdClass;
        }
        $cover_image = $entry->coverImage()->get()->filter(function ($item) {
            if ($item instanceof FileModel) {
                return $item;
            }
        });
        $cover_image_sizes = $this->request->get('cover_image_sizes');
        if (!$cover_image_sizes = $this->JsonSizes($cover_image_sizes)) {
            $cover_image_sizes = ['original'];
        }
        $cover_image = $this->get_adapter_size_image($cover_image,$cover_image_sizes);
        return $cover_image;
    }

    /**
     * 处理作者数据
     * @method getAuthor
     * @return array
     */
    public function getAuthor($authorId)
    {
        $fields = array(
            "id",
            "sort_order",
            "created_at",
            "created_by_id",
            "updated_at",
            "updated_by_id",
            "deleted_at",
            "username",
            "display_name",
            "first_name",
            "last_name",
            "activated",
            "enabled",
            "last_login_at",
            "last_activity_at",
            'avatar',
        );
        $user = app()->make(UserRepositoryInterface::class);
        $user = $user->find($authorId);
        $udata = $user->toArray();


        foreach ($fields as $k=>$v) {
            if (array_key_exists($v, $udata)) {
                $ret[$v] = $udata[$v];
            }
        }
        $avatar = $user->avatar()->first();
        $ret['avatar'] = $avatar ? $this->fieldDataImage($avatar) : '';

        return $ret;
    }
}

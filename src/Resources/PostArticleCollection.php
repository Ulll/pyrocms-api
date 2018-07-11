<?php

namespace Pyrocmsapi\Resources;

use Pyrocmsapi;

use Illuminate\Http\Resources\Json\ResourceCollection;
use Pyrocmsapi\Model\PostModel;
use Anomaly\Streams\Platform\Model\Posts\PostsArticlePostsEntryModel;
use Anomaly\FilesModule\File\FileModel;
use Anomaly\FilesModule\File\FileCollection;

use Anomaly\UsersModule\User\Contract\UserRepositoryInterface;

use Pyrocmsapi\Traits\JsonSizes;
use Pyrocmsapi\Traits\AdapterImage;

class PostArticleCollection extends ResourceCollection
{
    use JsonSizes;
    use AdapterImage;
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
            'data'   => $this->collection->map(function ($resource) {
                return $this->disposeArticleResource($resource);
            }),
        ];
    }


    /**
     * 拼接列表中的资源数据
     * @method disposeArticleResource
     * @param  PostModel              $post
     * @return array
     */
    public function disposeArticleResource(PostModel $post)
    {
        $result = array(
            'id'          => $post->getId(),
            'title'       => $post->getTitle(),
            'cover_image' => new \stdClass,
        );
        //获取PostArticlePostModel
        $entryCollection = $post->entry()->get();
        $entryCollection = $entryCollection->filter(function($item){
            if ($item instanceof PostsArticlePostsEntryModel) {
                return $item;
            }
        });
        $entry = $entryCollection[0];
        //处理封面
        if (method_exists($entry, 'coverImage')) {
            $cover_image = $entry->coverImage()->get();
            $result['cover_image'] = $this->getCoverImage($cover_image);
        }
        //处理作者数据
        $authorId = $post->getFieldValue('author');
        $result['author'] = $this->getAuthor($authorId);
        //文章收藏数
        $result['bookmark_num'] = rand(100,300);
        return $result;
    }

    /**
     * 格式化封面图片
     * @method formatCoverImage
     * @param  FileCollection   $cover_image
     * @return array
     */
    public function getCoverImage(FileCollection $cover_image)
    {
        $cover_image = $cover_image->filter(function ($item) {
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
        );
        $user = app()->make(UserRepositoryInterface::class);
        $user = $user->find($authorId);
        $udata = $user->toArray();
        foreach ($fields as $k=>$v) {
            if (array_key_exists($v, $udata)) {
                $ret[$v] = $udata[$v];
            }
        }
        return $ret;
    }
}

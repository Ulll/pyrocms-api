<?php

namespace Pyrocmsapi\Repository;

use Anomaly\PostsModule\Post\PostRepository as RepositoryPost;
use Pyrocmsapi\Model\PostModel;
use Anomaly\Streams\Platform\Addon\FieldType\FieldType;
use Pyrocmsapi\FieldDataAccessor\FieldType as FieldTypeAccessor;
use Anomaly\UsersModule\User\Contract\UserRepositoryInterface;
use Anomaly\Streams\Platform\Model\Posts\PostsDefaultPostsEntryModel;
use Anomaly\FilesModule\File\FileCollection;
use Anomaly\FilesModule\File\FileModel;
use Anomaly\FilesModule\File\Contract\FileInterface;
use Illuminate\Http\Request;
use Pyrocmsapi\Traits\JsonSizes;

/**
 * API专用的post repository
 */
class PostRepository extends RepositoryPost
{
    use JsonSizes;

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
     * Create a new PostRepository instance.
     *
     * @param PostModel $model
     */
    public function __construct(PostModel $model, Request $request)
    {
        parent::__construct($model);
        $this->model = $model;
        $this->request = $request;
    }

    /**
     * 获取首页文章列表
     * @method getIndexList
     * @param  integer $limit 10
     * @return array
     */
    public function getIndexList()
    {
        $limit = $this->request->get('limit');
        $ret = [];
        $items = $this->getRecent($limit);
        //获取ORM对象数组
        $items = $items->items();
        foreach ($items as $item) {
            $ret[] = $this->parsing($item);
        }
        // dd($ret);
        return $ret;
    }

    public function find($id)
    {
        $post = parent::find($id);
        return $this->parsing($post);
    }


    public function parsing(PostModel $post)
    {
        $this->model = $post;
        //获取post对象中的数据
        $result = $post->toArray();
        //获取PostDefaultPostModel
        $entryCollection = $post->entry()->get();
        $entryCollection = $entryCollection->filter(function($item){
            if ($item instanceof PostsDefaultPostsEntryModel) {
                return $item;
            }
        });
        $entry = $entryCollection[0];

        $result['cover_image'] = new \stdClass;
        //处理封面
        if (method_exists($entry, 'coverImage')) {
            $cover_image = $entry->coverImage()->get();
            $result['cover_image'] = $this->filterCoverImage($cover_image);
        }
        //处理作者数据
        $result['author'] = $this->getAuthor();
        unset($result['author_id']);
        //假数据
        $result['bookmark_num'] = rand(100,300);

        return $result;
        //获取自定义字段
        // $custromKeys = $entry->getAssignments()->fieldSlugs();
        // foreach ($custromKeys as $k => $fieldName) {
        //     $relationship = $entry->getFieldType($fieldName);
        //     $accessor = $this->getFieldDataAccessor($relationship);
        //     $result[$fieldName] = $accessor->getData();
        // }
    }


    // protected function getFieldDataAccessor(FieldType $fieldType)
    // {
    //     $className = get_class($fieldType);
    //     $suffix    = explode("\\", $className);
    //     $suffix    = end($suffix);
    //     $fieldTypeName = '\\Pyrocmsapi\\FieldDataAccessor\\'.$suffix;
    //     if (!class_exists($fieldTypeName)) {
    //         $fieldTypeName = FieldTypeAccessor::class;
    //     }
    //     $accessor = app()->make($fieldTypeName, ['fieldType' => $fieldType]);
    //     return $accessor;
    // }



    /**
     * 处理作者数据
     * @method getAuthor
     * @return array
     */
    public function getAuthor()
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
        $authorId = $this->model->getFieldValue('author');
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

    public function filterCoverImage(FileCollection $cover_image)
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
     * 从一系列图片中选出最匹配宽高比的图片
     * @method get_adapter_size_image
     * @param  FileCollection $images 待选图片
     * @param  array $thumb_sizes 目标尺寸
     * @return array
     */
    protected function get_adapter_size_image(FileCollection $images, array $thumb_sizes = ['original'])
    {
        //空图片
        if ($images->isEmpty()) {
            return new \stdClass;
        }
        $ret_images = array();
        //获取所有供选图片的宽高比
        foreach ($images as $k=>$image) {
            $orig_width    = $image->getWidth();
            $orig_height   = $image->getHeight();
            $orig_ratio    = $orig_width/$orig_height;
            $orig_ratios[] = $orig_ratio;
        }
        //默认返回裁图尺寸
        if (!$thumb_sizes) {
            $thumb_sizes = '["original"]';
        }
        //目标尺寸
        foreach ($thumb_sizes as $k=>$size) {
            $sizes = explode('_', $size);
            //不同时限定宽高，则直接取图片组第一张进行等比缩放
            if (count($sizes) != 2 || !$sizes[0] || !$sizes[1]) {
                $ret_images[$size] = array($this->fieldDataImage($images[0]));
            }else {
                //限定宽高，则比对出尺寸最接近的一张图片进行裁剪
                $need_ratio = $sizes[0]/$sizes[1];
                $tmp_dif = 99999;
                $tmp_key = 0;
                foreach ($orig_ratios as $k1=> $v1) {
                    $dif = abs($need_ratio-$v1);
                    if ($dif < $tmp_dif) {
                        $tmp_dif = $dif;
                        $tmp_key = $k1;
                    }
                }
                $ret_images[$size] = array($this->fieldDataImage($images[$tmp_key]));
            }
        }
        return $ret_images;
    }

    protected function fieldDataImage(FileInterface $file)
    {
        $ret['url']       = $file->url();
        $ret['name']      = $file->getName();
        $ret['type']      = $file->type();
        $ret['mimetype']  = $file->getMimeType();
        $ret['width']     = $file->getWidth();
        $ret['height']    = $file->getHeight();
        $ret['size']      = $file->getSize();
        $ret['extension'] = $file->getExtension();
        return $ret;
    }
}

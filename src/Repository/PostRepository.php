<?php

namespace Pyrocmsapi\Repository;

use Anomaly\PostsModule\Post\PostRepository as RepositoryPost;
use Pyrocmsapi\Model\PostModel;
use Anomaly\Streams\Platform\Addon\FieldType\FieldType;
use Pyrocmsapi\FieldDataAccessor\FieldType as FieldTypeAccessor;
use Anomaly\UsersModule\User\Contract\UserRepositoryInterface;

/**
 * API专用的post repository
 */
class PostRepository extends RepositoryPost
{

    /**
     * The post model.
     *
     * @var PostModel
     */
    protected $model;

    /**
     * Create a new PostRepository instance.
     *
     * @param PostModel $model
     */
    public function __construct(PostModel $model)
    {
        parent::__construct($model);
        $this->model = $model;
    }

    /**
     * 获取首页文章列表
     * @method getIndexList
     * @param  integer $limit 10
     * @return array
     */
    public function getIndexList($limit)
    {
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
        $entry = $post->entry()->first();
        //获取自定义字段
        $custromKeys = $entry->getAssignments()->fieldSlugs();
        foreach ($custromKeys as $k => $fieldName) {
            $relationship = $entry->getFieldType($fieldName);
            $accessor = $this->getFieldDataAccessor($relationship);
            $result[$fieldName] = $accessor->getData();
        }
        //处理作者数据
        $result['author'] = $this->getAuthor();
        unset($result['author_id']);
        return $result;
    }

    protected function getFieldDataAccessor(FieldType $fieldType)
    {
        $className = get_class($fieldType);
        $suffix    = explode("\\", $className);
        $suffix    = end($suffix);
        $fieldTypeName = '\\Pyrocmsapi\\FieldDataAccessor\\'.$suffix;
        if (!class_exists($fieldTypeName)) {
            $fieldTypeName = FieldTypeAccessor::class;
        }
        $accessor = app()->make($fieldTypeName, ['fieldType' => $fieldType]);
        return $accessor;
    }

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
}

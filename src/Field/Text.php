<?php

namespace Corcel\Acf\Field;

use Corcel\Acf\FieldInterface;
use Corcel\Post;
use Corcel\PostMeta;

/**
 * Class Text
 *
 * @package Corcel\Acf\Field
 * @author Junior Grossi <juniorgro@gmail.com>
 */
class Text implements FieldInterface
{
    /**
     * @var Post
     */
    protected $post;

    /**
     * @var PostMeta
     */
    protected $postMeta;

    /**
     * @var string
     */
    protected $fieldName;

    /**
     * @var string
     */
    protected $fieldKey;

    /**
     * @var string
     */
    protected $fieldType;

    /**
     * @param Post $post
     * @param string $fieldName
     */
    public function __construct(Post $post, $fieldName)
    {
        $this->post = $post;
        $this->postMeta = new PostMeta();
        $this->fieldName = $fieldName;
        $this->fieldKey = $this->fetchFieldKey($fieldName);
        $this->fieldType = $this->fetchFieldType($this->fieldKey);
    }

    /**
     * @return string|int
     */
    public function get()
    {
        $meta = $this->postMeta
            ->where('post_id', $this->post->id)
            ->where('meta_key', $this->fieldName)
            ->first();

        return $meta->meta_value;
    }

    /**
     * @param string $fieldName
     * @return string mixed
     */
    protected function fetchFieldKey($fieldName)
    {
        $postMeta = $this->postMeta->where('post_id', $this->post->id)->where('meta_key', '_'.$fieldName);

        return $postMeta->meta_value;
    }

    /**
     * @param string $fieldKey
     * @return string
     */
    protected function fetchFieldType($fieldKey)
    {
        $post = $this->post->where('post_name', $fieldKey)->first();
        $fieldData = unserialize($post->post_content);

        return isset($fieldData['type']) ? $fieldData['type'] : 'text';
    }
}
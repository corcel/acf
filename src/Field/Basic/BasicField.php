<?php

namespace Corcel\Acf\Field\Basic;

use Corcel\Post;
use Corcel\PostMeta;

/**
 * Class BasicField
 *
 * @package Corcel\Acf\Field
 * @author Junior Grossi <juniorgro@gmail.com>
 */
abstract class BasicField
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

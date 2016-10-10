<?php

namespace Corcel\Acf\Field;

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
    protected $name;

    /**
     * @var string
     */
    protected $key;

    /**
     * @var string
     */
    protected $type;

    /**
     * @var mixed
     */
    protected $value;

    /**
     * Constructor method
     */
    public function __construct()
    {
        $this->postMeta = new PostMeta();
    }

    /**
     * Get the value of a field according it's post ID
     *
     * @param string $field
     * @param Post $post
     * @return array|string
     */
    public function fetchValue($field, Post $post)
    {
        $postMeta = $this->postMeta->where('post_id', $post->ID)
            ->where('meta_key', $field)
            ->first();

        if (isset($postMeta->meta_value) and $postMeta->meta_value) {
            $value = $postMeta->meta_value;
            if ($array = @unserialize($value) and is_array($array)) {
                $this->value = $array;

                return $array;
            } else {
                $this->value = $value;

                return $value;
            }
        }
    }

    /**
     * @param string $fieldName
     * @param Post $post
     * @return string
     */
    public function fetchFieldKey($fieldName, Post $post)
    {
        $this->post = $post;
        $this->name = $fieldName;

        $postMeta = $this->postMeta->where('post_id', $post->ID)
            ->where('meta_key', '_'.$fieldName)
            ->first();

        $this->key = $postMeta->meta_value;

        return $this->key;
    }

    /**
     * @param string $fieldKey
     * @return string
     */
    public function fetchFieldType($fieldKey)
    {
        $post = $this->post->where('post_name', $fieldKey)->first();
        $fieldData = unserialize($post->post_content);

        $this->type = isset($fieldData['type']) ? $fieldData['type'] : 'text';

        return $this->type;
    }

    /**
     * @return mixed
     */
    public function __toString()
    {
        return $this->get();
    }
}

<?php

namespace Corcel\Acf\Field;

use Corcel\Post;
use Corcel\PostMeta;

/**
 * Class BasicField.
 *
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
     * @var string
     */
    protected $connection;

    /**
     * Constructor method.
     *
     * @param Post $post
     */
    public function __construct(Post $post)
    {
        $this->post = $post;
        $this->postMeta = new PostMeta();
        $this->postMeta->setConnection($post->getConnectionName());
    }

    /**
     * Get the value of a field according it's post ID.
     *
     * @param string $field
     *
     * @return array|string
     */
    public function fetchValue($field)
    {
        $postMeta = $this->postMeta->where('post_id', $this->post->ID)
            ->where('meta_key', $field)
            ->first();

        if (isset($postMeta->meta_value) and ! is_null($postMeta->meta_value)) {
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
     *
     * @return string
     */
    public function fetchFieldKey($fieldName)
    {
        $this->name = $fieldName;

        $postMeta = $this->postMeta->where('post_id', $this->post->ID)
            ->where('meta_key', '_' . $fieldName)
            ->first();

        if (!$postMeta) {
            return null;
        }

        $this->key = $postMeta->meta_value;

        return $this->key;
    }

    /**
     * @param string $fieldKey
     *
     * @return string|null
     */
    public function fetchFieldType($fieldKey)
    {
        $post = $this->post->orWhere(function ($query) use ($fieldKey) {
            $query->where('post_name', $fieldKey);
            $query->where('post_type', 'acf-field');
        })->first();

        if ($post) {
            $fieldData = unserialize($post->post_content);
            $this->type = isset($fieldData['type']) ? $fieldData['type'] : 'text';

            return $this->type;
        }

        return null;
    }

    /**
     * @return mixed
     */
    public function __toString()
    {
        return $this->get();
    }
}

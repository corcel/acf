<?php

namespace Corcel\Acf\Field;

use Corcel\Acf\FieldInterface;
use Corcel\Post;

/**
 * Class PostObject
 *
 * @package Corcel\Acf\Field
 * @author Junior Grossi <juniorgro@gmail.com>
 */
class PostObject extends BasicField implements FieldInterface
{
    /**
     * @var Post
     */
    protected $object;

    /**
     * @param string $fieldName
     * @param Post $post
     */
    public function process($fieldName, Post $post)
    {
        $postId = $this->fetchValue($fieldName, $post);

        if (is_array($postId)) {
            $this->object = $post->whereIn('ID', $postId)->get();
        } else {
            $this->object = $post->find($postId);
        }
    }

    /**
     * @return mixed
     */
    public function get()
    {
        return $this->object;
    }
}

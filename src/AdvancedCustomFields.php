<?php

namespace Corcel\Acf;

use Corcel\Post;

/**
 * Class AdvancedCustomFields
 *
 * @package Corcel\Acf
 * @author Junior Grossi <juniorgro@gmail.com>
 */
class AdvancedCustomFields
{
    /**
     * @var Post
     */
    protected $post;

    /**
     * @param Post $post
     */
    public function __construct(Post $post)
    {
        $this->post = $post;
    }

    /**
     * @return mixed
     */
    public function get($fieldName)
    {
        $field = FieldFactory::make($fieldName, $this->post);

        return $field->get();
    }

    /**
     * @param string $name
     * @return mixed
     */
    function __get($name)
    {
        return $this->get($name);
    }
}

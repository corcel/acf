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
     * @var string
     */
    protected $fieldName;

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
     * @param string $name
     */
    public function field($name)
    {
        $this->fieldName = $name;

        return $this;
    }

    /**
     * @return mixed
     */
    public function get()
    {
        $field = FieldFactory::make($this->fieldName, $this->post);

        return $field->get();
    }
}

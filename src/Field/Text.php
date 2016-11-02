<?php

namespace Corcel\Acf\Field;

use Corcel\Acf\FieldInterface;
use Corcel\Post;

/**
 * Class Text.
 *
 * @author Junior Grossi <juniorgro@gmail.com>
 */
class Text extends BasicField implements FieldInterface
{
    /**
     * @var string
     */
    protected $value;

    public function process($field, Post $post)
    {
        $this->value = $this->fetchValue($field, $post);
    }

    /**
     * @return string
     */
    public function get()
    {
        return $this->value;
    }
}

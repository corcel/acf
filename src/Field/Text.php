<?php

namespace Corcel\Acf\Field;

use Corcel\Acf\FieldInterface;

/**
 * Class Text
 *
 * Used for the following fields: text, textarea, number, email, url, password, wysiwyg
 *
 * @package Corcel\Acf\Field
 * @author Junior Grossi <juniorgro@gmail.com>
 */
class Text extends BasicField implements FieldInterface
{
    protected $meta;

    /**
     * @return string
     */
    public function get()
    {
        return $this->meta->meta_value;
    }

    public function build()
    {
        $this->meta = $this->postMeta
            ->where('post_id', $this->post->ID)
            ->where('meta_key', $this->fieldName)
            ->first();
    }
}
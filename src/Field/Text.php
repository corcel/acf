<?php

namespace Corcel\Acf\Field;

use Corcel\Acf\FieldInterface;

/**
 * Class Text
 *
 * @package Corcel\Acf\Field
 * @author Junior Grossi <juniorgro@gmail.com>
 */
class Text implements FieldInterface
{
    /**
     * @return string
     */
    public function get()
    {
        $meta = $this->postMeta
            ->where('post_id', $this->post->id)
            ->where('meta_key', $this->fieldName)
            ->first();

        return $meta->meta_value;
    }
}
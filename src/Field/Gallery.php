<?php

namespace Corcel\Acf\Field;

use Corcel\Acf\FieldInterface;
use Illuminate\Support\Collection;

/**
 * Class Gallery
 *
 * @package Corcel\Acf\Field
 * @author Junior Grossi <juniorgro@gmail.com>
 */
class Gallery extends BasicField implements FieldInterface
{
    /**
     * @var array
     */
    protected $images = [];

    /**
     * @var Collection
     */
    protected $collection;

    /**
     * @return void
     */
    public function build()
    {
        $galleryMeta = $this->postMeta->where('post_id', $this->post->ID)
            ->where('meta_key', $this->fieldName)
            ->first();

        $ids = unserialize($galleryMeta->meta_value);
        $files = $this->post->whereIn('ID', $ids)->get();

        foreach ($files as $post) {
            $image = new Image($post);
            $image->loadDataFromCurrentPost();
            $image->build();
            $this->images[] = $image;
        }
    }

    /**
     * @return Collection
     */
    public function get()
    {
        if (!$this->collection instanceof Collection) {
            $this->collection = new Collection($this->images);
        }

        return $this->collection;
    }
}
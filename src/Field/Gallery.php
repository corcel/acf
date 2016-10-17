<?php

namespace Corcel\Acf\Field;

use Corcel\Acf\FieldInterface;
use Corcel\Post;
use Illuminate\Support\Collection;

/**
 * Class Gallery
 *
 * @package Corcel\Acf\Field
 * @author Junior Grossi <juniorgro@gmail.com>
 */
class Gallery extends Image implements FieldInterface
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
     * @param $field
     * @param Post $post
     */
    public function process($field, Post $post)
    {
        $ids = $this->fetchValue($field, $post);
        $attachments = $post->whereIn('ID', $ids)->get();
        $metaDataValues = $this->fetchMultipleMetadataValues($attachments);

        foreach ($attachments as $attachment) {
            $image = new Image();
            $image->fillFields($attachment);
            $image->fillMetadataFields($metaDataValues[$attachment->ID]);
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

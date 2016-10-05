<?php

namespace Corcel\Acf\Field;

use Corcel\Acf\FieldInterface;
use stdClass;

/**
 * Class Image
 *
 * @package Corcel\Acf\Field
 * @author Junior Grossi
 */
class Image extends BasicField implements FieldInterface
{
    /**
     * @var int
     */
    public $width;

    /**
     * @var int
     */
    public $height;

    /**
     * @var string
     */
    public $filename;

    /**
     * @var array
     */
    protected $sizes = [];

    public function build()
    {
        $meta = $this->postMeta->where('post_id', $this->post->ID)
            ->where('meta_key', $this->fieldName)
            ->first();

        $meta = $this->postMeta->where('post_id', $meta->meta_value)
            ->where('meta_key', '_wp_attachment_metadata')
            ->first();

        $imageData = unserialize($meta->meta_value);
        $this->fillFields($imageData);
    }

    public function get()
    {
        return $this;
    }

    protected function fillFields($data)
    {
        $this->filename = $data['file'];
        $this->width = $data['width'];
        $this->height = $data['height'];
        $this->sizes = $data['sizes'];
    }

    public function size($size)
    {
        if (isset($this->sizes[$size])) {
            $data = $this->sizes[$size];
            $size = $this->getImageMetaData($data);

            return $size;
        }

        $data = $this->size('thumbnail');

        return $this->getImageMetaData($data);
    }

    /**
     * @param array $data
     * @return stdClass
     */
    private function getImageMetaData(array $data)
    {
        $size = new stdClass();
        $size->filename = $data['file'];
        $size->width = $data['width'];
        $size->height = $data['height'];
        $size->mime_type = $data['mime-type'];

        return $size;
    }

}